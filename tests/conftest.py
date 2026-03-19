"""
Pytest configuration and shared fixtures.
Skips integration tests when SKIP_INTEGRATION_TESTS=1 (e.g. inside API container without Docker).
"""
import os
import pytest


def pytest_collection_modifyitems(config, items):
    """Skip integration tests when SKIP_INTEGRATION_TESTS is set (e.g. in API container)."""
    skip_integration = os.environ.get("SKIP_INTEGRATION_TESTS", "").strip().lower() in (
        "1",
        "true",
        "yes",
    )
    if not skip_integration:
        return
    skip_marker = pytest.mark.skip(reason="Integration tests disabled (SKIP_INTEGRATION_TESTS=1)")
    for item in items:
        if "integration" in item.keywords:
            item.add_marker(skip_marker)
