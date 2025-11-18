import os
import re
from datetime import datetime
from collections import Counter, defaultdict
import matplotlib.pyplot as plt
from matplotlib.backends.backend_pdf import PdfPages

BASE_DIR   = os.path.dirname(__file__)
ACCESS_LOG = os.path.join(BASE_DIR, "sim_logs", "access_simulated.log")
ERROR_LOG  = os.path.join(BASE_DIR, "sim_logs", "error_simulated.log")
OUTPUT_PDF = os.path.join(BASE_DIR, "log_report.pdf")

plt.rcParams.update
({
    "figure.figsize": (12, 4),
    "axes.grid": True,
    "axes.titlesize": 14,
    "axes.labelsize": 10,
    "xtick.labelsize": 8,
    "ytick.labelsize": 8,
})

#helper functions
def read_lines(path):
    if not os.path.exists(path):
        print(f"[WARN] Log file not found: {path}")
        return []
    with open(path, "r", encoding="utf-8", errors="ignore") as f:
        return [ln.rstrip("\n") for ln in f]

def user_agent_to_browser(ua):
    ua = (ua or "").strip().lower()
    if not ua or ua == "-":
        return "Unknown"
    if "edg" in ua or "edge" in ua:
        return "Edge"
    if "opr" in ua or "opera" in ua:
        return "Opera"
    if "chrome" in ua and "safari" in ua:
        return "Chrome"
    if "firefox" in ua:
        return "Firefox"
    if "safari" in ua and "chrome" not in ua:
        return "Safari"
    return "Other"

#access log parsing
ACCESS_RE = re.compile
(
    r'(?P<ip>\S+)\s+-\s+-\s+\[(?P<ts>[^\]]+)\]\s+'
    r'"(?P<method>\S+)\s+(?P<path>\S+)\s+(?P<proto>[^"]+)"\s+'
    r'(?P<status>\d{3})\s+(?P<bytes>\S+)\s+'
    r'"(?P<referer>[^"]*)"\s+"(?P<agent>[^"]*)"'
)

def parse_apache_ts(ts):
    for fmt in ("%d/%b/%Y:%H:%M:%S %z", "%d/%b/%Y:%H:%M:%S"):
        try:
            dt = datetime.strptime(ts, fmt)
            return dt.replace(tzinfo=None)
        except ValueError:
            pass
    raise ValueError(f"Unrecognized access timestamp: {ts}")

def parse_access(lines):
    entries = []
    unmatched = 0
    for ln in lines:
        if not ln.strip():
            continue
        m = ACCESS_RE.search(ln)
        if not m:
            unmatched += 1
            continue
        d = m.groupdict()
        try:
            dt = parse_apache_ts(d["ts"])
        except ValueError:
            unmatched += 1
            continue
        entries.append
	({
            "ip": d["ip"],
            "datetime": dt,
            "method": d["method"],
            "path": os.path.basename(d["path"]),
            "proto": d["proto"],
            "status": int(d["status"]),
            "bytes": 0 if d["bytes"] == "-" else int(d["bytes"]),
            "referer": d["referer"],
            "browser": user_agent_to_browser(d["agent"]),
        })
    if unmatched:
        print(f"[INFO] Access lines unmatched (ignored): {unmatched}")
    return entries

#error log parsing
ERROR_RE = re.compile
(
    r'^\[(?P<dow>\w{3})\s+(?P<mon>\w{3})\s+(?P<day>\d{1,2})\s+(?P<hms>\d{2}:\d{2}:\d{2})\s+(?P<year>\d{4})\]\s+'
    r'\[(?P<engine>[^\]:]+):(?P<severity>[^\]]+)\]\s+\[pid\s+\d+\]\s+\[client\s+(?P<ip>[\d\.]+):\d+\]\s+'
    r'(?P<msg>.*)$'
)
MONTHS = {m: i for i, m in enumerate
	 (["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"], 1)}

def parse_error_ts(mon, day, hms, year):
    mm = MONTHS.get(mon, 1)
    ds = int(day)
    dt_str = f"{year}-{mm:02d}-{ds:02d} {hms}"
    return datetime.strptime(dt_str, "%Y-%m-%d %H:%M:%S")

def severity_bucket(sev):
    s = (sev or "").lower()
    if "fatal" in s or "error" in s:
        return "Error/Fatal"
    if "warn" in s:
        return "Warning"
    if "notice" in s:
        return "Notice"
    return "Other"

FILE_RE = re.compile(r'/[^\s:]+\.php')

def extract_php_file(msg):
    m = FILE_RE.search(msg)
    return os.path.basename(m.group(0)) if m else "(unknown)"

def parse_errors(lines):
    entries = []
    unmatched = 0
    for ln in lines:
        if not ln.strip():
            continue
        m = ERROR_RE.search(ln)
        if not m:
            unmatched += 1
            continue
        g = m.groupdict()
        try:
            dt = parse_error_ts(g["mon"], g["day"], g["hms"], g["year"])
        except ValueError:
            unmatched += 1
            continue
        entries.append({
            "datetime": dt,
            "ip": g["ip"],
            "severity": g["severity"],
            "severity_bucket": severity_bucket(g["severity"]),
            "file": extract_php_file(g["msg"]),
            "message": g["msg"],
        })
    if unmatched:
        print(f"[INFO] Error lines unmatched (ignored): {unmatched}")
    return entries

def draw_table(fig, col_labels, rows, title):
    ax = fig.add_subplot(111)
    ax.axis("off")
    ax.set_title(title, pad=30)
    table = ax.table(cellText=rows, colLabels=col_labels, loc="center")
    table.auto_set_font_size(False)
    table.set_fontsize(8)
    table.scale(1, 1.2)

def plot_line(fig, x, y, title, xlabel, ylabel, color):
    ax = fig.add_subplot(111)
    ax.plot(x, y, marker="o", linewidth=1.5, color=color)
    ax.set_title(title, pad=20)
    ax.set_xlabel(xlabel)
    ax.set_ylabel(ylabel)
    plt.xticks(rotation=45, ha='right')
    plt.subplots_adjust(bottom=0.2)

#PDF builder
def build_pdf(access, errors):
    with PdfPages(OUTPUT_PDF) as pdf:
        fig = plt.figure(figsize=(12, 4))
        fig.suptitle("Web Log Analysis Report — rbelachew", fontsize=16, y=0.98)
        ax = fig.add_subplot(111)
        ax.axis("off")
        overview = [
            f"Total Access Requests: {len(access)}",
            f"Unique Pages: {len(set(a['path'] for a in access))}",
            f"Unique IPs: {len(set(a['ip'] for a in access))}",
            f"Access Time Range: {min(a['datetime'] for a in access) if access else 'N/A'} → {max(a['datetime'] for a in access) if access else 'N/A'}",
            "",
            f"Total Error Events: {len(errors)}",
            f"Error IPs: {len(set(e['ip'] for e in errors))}",
            f"Error Time Range: {min(e['datetime'] for e in errors) if errors else 'N/A'} → {max(e['datetime'] for e in errors) if errors else 'N/A'}",
        ]
        ax.text(0.02, 0.95, "Summary", fontsize=14, weight="bold", transform=ax.transAxes, va="top")
        ax.text(0.02, 0.80, "\n".join(overview), fontsize=10, transform=ax.transAxes, va="top")
        pdf.savefig(fig); plt.close(fig)

        if access:
            access_rows = []
            grouped = defaultdict(lambda: defaultdict(list))
            for a in access:
                grouped[a["path"]][a["ip"]].append(a["browser"])
            for path, ips in grouped.items():
                for ip, browsers in ips.items():
                    access_rows.append([path, ip, str(len(browsers)), ", ".join(sorted(set(browsers)))])
            fig = plt.figure(figsize=(12, max(4, 0.25*len(access_rows)+1)))
            draw_table(fig, ["Page", "IP Address", "Count", "Browsers"], access_rows, "Access Log Page by IP")
            pdf.savefig(fig); plt.close(fig)
            daily_access = defaultdict(int)
            for a in access:
                day = a["datetime"].strftime("%Y-%m-%d")
                daily_access[day] += 1
            if daily_access:
                fig = plt.figure(figsize=(12, 4))
                plot_line(fig, list(sorted(daily_access.keys())), [daily_access[d] for d in sorted(daily_access.keys())],
                          "Access Timeline (by Day)", "Day", "Requests", "#1f77b4")
                pdf.savefig(fig); plt.close(fig)

         if errors:
            error_rows = []
            grouped_err = defaultdict(list)
            for e in errors:
                grouped_err[e["ip"]].append(e["message"])
            for ip, msgs in grouped_err.items():
                error_rows.append([ip, str(len(msgs))])
            fig = plt.figure(figsize=(12, max(4, 0.25*len(error_rows)+1)))
            draw_table(fig, ["IP Address", "Count"], error_rows, "Error Log — IP Summary")
            pdf.savefig(fig); plt.close(fig)
            daily_errors = defaultdict(int)
            for e in errors:
                day = e["datetime"].strftime("%Y-%m-%d")
                daily_errors[day] += 1
            if daily_errors:
                fig = plt.figure(figsize=(12, 4))
                plot_line(fig, list(sorted(daily_errors.keys())), [daily_errors[d] for d in sorted(daily_errors.keys())],
                          "Error Timeline (by Day)", "Day", "Errors", "#d62728")
                pdf.savefig(fig); plt.close(fig)
    print(f"\n[OK] Created PDF: {OUTPUT_PDF}")

if __name__ == "__main__":
    access_lines = read_lines(ACCESS_LOG)
    error_lines  = read_lines(ERROR_LOG)
    access = parse_access(access_lines)
    errors = parse_errors(error_lines)
    print(f"Loaded access entries: {len(access)}")
    print(f"Loaded error entries : {len(errors)}")
    build_pdf(access, errors)
