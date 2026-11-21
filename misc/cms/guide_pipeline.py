#!/usr/bin/env python3
"""Create review-only guide JSON for an existing PHP MVC / RAWG game page.

This is the bridge from the *research* collector to PHP, not an AI writer.
It never copies wiki excerpts into publishable guide text, and never publishes.

Examples (run from your project root):
  python guide_pipeline.py --game-id 3498 --demo
  python guide_pipeline.py --game-id 3498 --research guide_research.json --slug prologue
  python guide_pipeline.py --game-id 3498 --slug prologue --title Prologue \
      --section 'Introduction::Write verified instructions here'

Output: storage/guides/<RAWG-ID>/<slug>.json
"""
import argparse
import json
import re
from datetime import datetime, timezone
from pathlib import Path
from urllib.parse import urlparse


def make_slug(value: str) -> str:
    value = value.strip().lower()
    value = re.sub(r"[^a-z0-9]+", "-", value).strip("-")
    if not value:
        raise ValueError("Slug cannot be empty")
    return value


def research_outline(path: Path):
    """Turn research metadata into an EMPTY outline, not a copied walkthrough."""
    records = json.loads(path.read_text(encoding="utf-8"))
    if not isinstance(records, list):
        raise ValueError("Research file must contain a JSON list")
    headings, sources = [], []
    for record in records:
        if not isinstance(record, dict) or record.get("error"):
            continue
        source = record.get("source_url", "")
        if isinstance(source, str) and urlparse(source).scheme in ("http", "https"):
            sources.append(source)
        for heading in record.get("headings", []):
            if isinstance(heading, str) and heading.strip() and heading not in headings:
                headings.append(heading.strip())
    # Metadata-only outline; an editor must verify and author every step.
    return [{"heading": heading, "text": ""} for heading in headings[:30]], list(dict.fromkeys(sources))


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--game-id", type=int, required=True, help="Existing RAWG game ID")
    parser.add_argument("--slug", help="Guide URL slug, e.g. prologue")
    parser.add_argument("--title", help="Guide title")
    parser.add_argument("--type", choices=("mission", "secret", "collectible", "walkthrough"), default="mission")
    parser.add_argument("--section", action="append", default=[], help="Heading::Text (repeatable)")
    parser.add_argument("--research", type=Path, help="JSON produced by wiki_guide_prototype.py")
    parser.add_argument("--demo", action="store_true", help="Create a clearly labeled demonstration draft")
    parser.add_argument("--out-dir", type=Path, default=Path("storage/guides"), help="Project guide storage root")
    args = parser.parse_args()
    if args.game_id <= 0:
        parser.error("--game-id must be a positive RAWG game ID")
    if args.demo and (args.research or args.section):
        parser.error("--demo cannot be combined with --research or --section")

    title = args.title or ("Prologue (demo)" if args.demo else "Untitled guide")
    slug = make_slug(args.slug or ("prologue" if args.demo else title))
    sections, sources = [], []
    if args.research:
        sections, sources = research_outline(args.research)
    for item in args.section:
        if "::" not in item:
            parser.error("Every --section must be formatted Heading::Text")
        heading, body = item.split("::", 1)
        if not heading.strip():
            parser.error("Section heading cannot be empty")
        sections.append({"heading": heading.strip(), "text": body.strip()})
    if args.demo:
        sections = [
            {"heading": "Overview", "text": "DEMO CONTENT — replace with verified walkthrough text."},
            {"heading": "Steps", "text": "DEMO CONTENT — insert reviewed steps here."},
        ]
    guide = {
        "schema_version": 1,
        "game_id": args.game_id,
        "slug": slug,
        "title": title,
        "type": args.type,
        "status": "draft",  # Display only in local development until reviewed.
        "sections": sections,
        "sources": sources,
        "generated_at_utc": datetime.now(timezone.utc).isoformat(),
    }
    dest = args.out_dir / str(args.game_id) / (slug + ".json")
    dest.parent.mkdir(parents=True, exist_ok=True)
    dest.write_text(json.dumps(guide, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
    print(f"Created DRAFT: {dest}")
    print(f"Sections: {len(sections)}; Sources: {len(sources)}. Nothing was published.")


if __name__ == "__main__":
    main()
