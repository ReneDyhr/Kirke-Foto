#!/usr/bin/env bash
# Regenerate RemoteIPInternalProxy lines from Cloudflare's published IP lists.
# Sources: https://www.cloudflare.com/ips-v4 https://www.cloudflare.com/ips-v6
set -euo pipefail

OUT="${1:-$(dirname "$0")/apache-remoteip-cloudflare.conf}"
V4_URL="https://www.cloudflare.com/ips-v4"
V6_URL="https://www.cloudflare.com/ips-v6"

tmp="$(mktemp)"
trap 'rm -f "$tmp"' EXIT

{
  echo "# Generated from ${V4_URL} and ${V6_URL} — do not edit by hand."
  echo "# Refresh: docker/production/update-cloudflare-remoteip.sh"
  echo
  # Cloudflare's text files omit a final newline; awk still emits the last CIDR.
  curl -fsS "$V4_URL" | awk 'NF { print "RemoteIPInternalProxy " $0 }'
  curl -fsS "$V6_URL" | awk 'NF { print "RemoteIPInternalProxy " $0 }'
} >"$tmp"

# mktemp uses 0600; Apache must read this as www-data.
chmod 644 "$tmp"
mv "$tmp" "$OUT"
trap - EXIT
