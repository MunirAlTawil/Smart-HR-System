from __future__ import annotations

from dataclasses import dataclass
from datetime import date
from pathlib import Path


@dataclass
class PdfObject:
    num: int
    body: bytes


def _pdf_escape(s: str) -> str:
    return (
        s.replace("\\", "\\\\")
        .replace("(", "\\(")
        .replace(")", "\\)")
        .replace("\r", "")
    )


def _build_minimal_pdf(lines: list[str]) -> bytes:
    """
    Minimal, self-contained PDF generator (no dependencies).
    Produces a single page with Helvetica text.
    """
    # Build a simple text content stream.
    # Use points; start near top-left and move down.
    y = 760
    leading = 14
    content_ops: list[str] = ["BT", "/F1 11 Tf", "72 {} Td".format(y)]
    for i, line in enumerate(lines):
        if i == 0:
            content_ops.append(f"({_pdf_escape(line)}) Tj")
        else:
            content_ops.append(f"0 -{leading} Td")
            content_ops.append(f"({_pdf_escape(line)}) Tj")
    content_ops.append("ET")
    content = ("\n".join(content_ops) + "\n").encode("latin-1", errors="replace")

    objects: list[PdfObject] = []

    # 1: Catalog
    objects.append(PdfObject(1, b"<< /Type /Catalog /Pages 2 0 R >>"))
    # 2: Pages
    objects.append(PdfObject(2, b"<< /Type /Pages /Kids [3 0 R] /Count 1 >>"))
    # 3: Page
    objects.append(
        PdfObject(
            3,
            b"<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] "
            b"/Resources << /Font << /F1 4 0 R >> >> "
            b"/Contents 5 0 R >>",
        )
    )
    # 4: Font
    objects.append(PdfObject(4, b"<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>"))
    # 5: Contents
    objects.append(
        PdfObject(
            5,
            b"<< /Length "
            + str(len(content)).encode("ascii")
            + b" >>\nstream\n"
            + content
            + b"endstream",
        )
    )

    # Assemble PDF with xref.
    out = bytearray()
    out.extend(b"%PDF-1.4\n%\xe2\xe3\xcf\xd3\n")

    offsets: dict[int, int] = {0: 0}
    for obj in objects:
        offsets[obj.num] = len(out)
        out.extend(f"{obj.num} 0 obj\n".encode("ascii"))
        out.extend(obj.body)
        out.extend(b"\nendobj\n")

    xref_start = len(out)
    out.extend(b"xref\n")
    out.extend(f"0 {len(objects)+1}\n".encode("ascii"))
    out.extend(b"0000000000 65535 f \n")
    for i in range(1, len(objects) + 1):
        out.extend(f"{offsets[i]:010d} 00000 n \n".encode("ascii"))

    out.extend(b"trailer\n")
    out.extend(f"<< /Size {len(objects)+1} /Root 1 0 R >>\n".encode("ascii"))
    out.extend(b"startxref\n")
    out.extend(f"{xref_start}\n".encode("ascii"))
    out.extend(b"%%EOF\n")
    return bytes(out)


def main() -> None:
    today = date.today().isoformat()

    # Chosen job for testing: Senior Laravel Developer (high keyword overlap)
    lines = [
        "JOB APPLICATION / CV (Test File) - Target role: Senior Laravel Developer",
        f"Date: {today}",
        "",
        "Candidate: Youssef El Amrani",
        "Email: youssef.elamrani.test@example.com | Phone: +212 6 12 34 56 78",
        "Location: Casablanca, Morocco (Open to Hybrid/Remote)",
        "",
        "Professional Summary",
        "Senior PHP/Laravel developer with 6+ years experience building REST APIs and web apps.",
        "Strong background in MySQL optimization, authentication/authorization, and automated testing.",
        "Comfortable with Git workflows, CI/CD, and performance/security best practices.",
        "",
        "Core Skills (high match)",
        "Laravel, PHP 8, MySQL, REST APIs, Git, PHPUnit, Feature Tests, TDD, SOLID, OOP",
        "API Integration, Queues/Jobs, Caching (Redis), Authentication (Sanctum/JWT), Docker, Linux",
        "",
        "Relevant Experience",
        "Senior Laravel Developer | 2021 - 2026",
        "- Built and maintained Laravel services for recruitment and HR workflows (jobs, applications).",
        "- Designed REST APIs, integrated third-party services, improved performance and security.",
        "- Implemented automated tests (PHPUnit) and code reviews; mentored junior developers.",
        "",
        "Backend Developer (PHP) | 2019 - 2021",
        "- Developed APIs and web dashboards; optimized SQL queries and indexes in MySQL.",
        "- Implemented role-based access control and audit logging.",
        "",
        "Selected Projects",
        "1) HR Portal API (Laravel + MySQL): recruitment, leave, attendance modules, reporting dashboards.",
        "2) CV Parser Integration: consumed NLP service via REST, stored parsed skills/experience fields.",
        "",
        "Education",
        "BSc in Computer Science",
        "",
        "Keywords for matching (explicit)",
        "Laravel, PHP, MySQL, REST API, Git, Testing, PHPUnit, CI/CD, Performance, Security",
        "",
        "Expected Match: > 70%",
    ]

    pdf_bytes = _build_minimal_pdf(lines)
    out_path = Path(__file__).resolve().parents[1] / "test.pdf"
    out_path.write_bytes(pdf_bytes)


if __name__ == "__main__":
    main()

