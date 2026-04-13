#!/usr/bin/env bash
# Build the static site and push dist/ to the gh-pages branch.
# Requires: php, rsync, git. Must be run inside the diary-web repo.

set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$REPO_ROOT"

WT_DIR="$(mktemp -d -t diary-gh-pages-XXXXXX)"
trap 'git worktree remove --force "$WT_DIR" 2>/dev/null || rm -rf "$WT_DIR"' EXIT

echo "==> Building dist/"
php build.php

echo "==> Preparing gh-pages worktree at $WT_DIR"
git fetch origin gh-pages 2>/dev/null || true
if git show-ref --verify --quiet refs/remotes/origin/gh-pages; then
    git worktree add -B gh-pages "$WT_DIR" origin/gh-pages
else
    git worktree add --orphan -b gh-pages "$WT_DIR"
    # Orphan worktree has no parent; clean staged state
    (cd "$WT_DIR" && git rm -rf --cached --ignore-unmatch . >/dev/null 2>&1 || true)
fi

echo "==> Syncing dist/ into worktree"
rsync -a --delete --exclude='.git' dist/ "$WT_DIR/"

echo "==> Committing and pushing gh-pages"
cd "$WT_DIR"
git add -A
if git diff --cached --quiet; then
    echo "No changes to deploy."
else
    git commit -m "Deploy: $(date -u +%Y-%m-%dT%H:%M:%SZ) from $(git -C "$REPO_ROOT" rev-parse --short HEAD)"
    git push -u origin gh-pages
    if command -v gh >/dev/null 2>&1; then
        echo "==> Requesting Pages rebuild"
        gh api -X POST repos/holly382/za-polarni-kruh/pages/builds >/dev/null 2>&1 || true
    fi
fi
echo "==> Done."
