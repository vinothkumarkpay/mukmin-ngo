#!/usr/bin/env python3
"""Build Impact Insights hero PNGs from each moments folder Feature image."""

from __future__ import annotations

import re
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
MOMENTS = ROOT / "public" / "welfare" / "img" / "moments"
OUT_DIR = ROOT / "public" / "welfare" / "img" / "news" / "insights"

NEWS_FOLDERS = {
    1: "Majlis Rumah Terbuka MUKMIN",
    2: "SIRAT Leaders Forum",
    3: "SIRAT Youth Summit",
    4: "FIKRAH Launch",
    5: "FIKRAH Global Roundtable",
    6: "MUKMIN Future Leaders Scholarship Pledge",
    7: "SIRAT Global Forum 2026",
    8: "The KL Declaration",
    9: "Kembara Ramadhan MUKMIN",
    10: "Majlis Berbuka Puasa & Kembara Ramadhan MUKMIN Penang",
    11: "Ramadhan Assistance for Religious Scholars & Ustaz",
    12: "Majlis Berbuka Puasa & Kembara Ramadhan MUKMIN Kuala Lumpur",
    13: "Takbir Raya MUKMIN",
    14: "Youth Icon Awards",
    15: "MUKMIN AGM",
    16: "India High Comm",
    17: "Golden Dinar Awards",
    18: "FIKRAH Chai & Chat",
    19: "Shark Tank pitching",
    20: "Football Match MUKMIN",
    21: "Jersey Launch",
}

HERO_FILES = {
    1: "13.png",
    2: "SIRAT.png",
    3: "11.png",
    4: "10.png",
    5: "9.png",
    6: "8.png",
    7: "7.png",
    8: "6.png",
    9: "5.png",
    10: "4.png",
    11: "3.png",
    12: "2.png",
    13: "1.png",
    14: "14.png",
    15: "15.png",
    16: "16.png",
    17: "17.png",
    18: "18.png",
    19: "19.png",
    20: "20.png",
    21: "21.png",
}


def find_feature(folder: str) -> Path | None:
    folder_path = MOMENTS / folder
    if not folder_path.is_dir():
        return None

    features = sorted(
        [
            path
            for path in folder_path.iterdir()
            if path.is_file() and re.match(r"^feature", path.stem, re.I)
        ],
        key=lambda path: path.name.lower(),
    )
    return features[0] if features else None


def crop_to_banner(image: Image.Image, width: int, height: int) -> Image.Image:
    target_ratio = width / height
    img_width, img_height = image.size
    source_ratio = img_width / img_height

    if source_ratio > target_ratio:
        new_width = int(img_height * target_ratio)
        left = (img_width - new_width) // 2
        box = (left, 0, left + new_width, img_height)
    else:
        new_height = int(img_width / target_ratio)
        top = (img_height - new_height) // 2
        box = (0, top, img_width, top + new_height)

    return image.crop(box).resize((width, height), Image.Resampling.LANCZOS)


def main() -> int:
    reference = OUT_DIR / "9.png"
    if not reference.exists():
        raise SystemExit(f"Reference hero not found: {reference}")

    target_width, target_height = Image.open(reference).size
    OUT_DIR.mkdir(parents=True, exist_ok=True)

    for tab, folder in NEWS_FOLDERS.items():
        source = find_feature(folder)
        if source is None:
            print(f"SKIP tab {tab}: no Feature image in {folder}")
            continue

        image = Image.open(source).convert("RGB")
        hero = crop_to_banner(image, target_width, target_height)
        output = OUT_DIR / HERO_FILES[tab]
        hero.save(output, "PNG", optimize=True)
        print(f"tab {tab:2d} -> {output.name} ({folder}/{source.name})")

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
