#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
SPEC_PATH="${ROOT_DIR}/sdk/openapi/a2a-registry.v1.json"
OUTPUT_PATH="${ROOT_DIR}/sdk/python"
CONFIG_PATH="${ROOT_DIR}/sdk/openapi/python-client-config.yml"
PYTHON_USER_BIN="$(python3 -m site --user-base)/bin"
PYTHON_CLIENT_BIN="${PYTHON_USER_BIN}/openapi-python-client"

rm -rf "${OUTPUT_PATH}"

if [[ ! -x "${PYTHON_CLIENT_BIN}" ]]; then
    python3 -m pip install --user --disable-pip-version-check openapi-python-client==0.26.2
fi

"${PYTHON_CLIENT_BIN}" generate \
    --path "${SPEC_PATH}" \
    --meta poetry \
    --config "${CONFIG_PATH}" \
    --output-path "${OUTPUT_PATH}" \
    --overwrite

rm -f "${ROOT_DIR}/sdk/python/README.md"

ROOT_DIR="${ROOT_DIR}" python3 - <<'PY'
import os
from pathlib import Path

pyproject = Path(os.environ["ROOT_DIR"]) / "sdk/python/pyproject.toml"
content = pyproject.read_text()
content = content.replace('readme = "README.md"\n', '')
content = content.replace('include = ["CHANGELOG.md", "a2a_registry_sdk/py.typed"]\n', 'include = ["a2a_registry_sdk/py.typed"]\n')
pyproject.write_text(content)
PY
