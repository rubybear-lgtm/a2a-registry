#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
SPEC_PATH="${ROOT_DIR}/sdk/openapi/a2a-registry.v1.json"
OUTPUT_PATH="${ROOT_DIR}/sdk/python"
CONFIG_PATH="${ROOT_DIR}/sdk/openapi/python-client-config.yml"
PYTHON_USER_BIN="$(python3 -m site --user-base)/bin"
PYTHON_CLIENT_BIN="${PYTHON_USER_BIN}/openapi-python-client"

# Preserve custom files that the generator would wipe
CUSTOM_README="${OUTPUT_PATH}/README.md"
CUSTOM_REGISTRY_CLIENT="${OUTPUT_PATH}/a2a_registry_sdk/registry_client.py"
CUSTOM_PYPROJECT="${OUTPUT_PATH}/pyproject.toml"

TMP_README=""
TMP_REGISTRY_CLIENT=""
TMP_PYPROJECT=""

if [[ -f "${CUSTOM_README}" ]]; then
    TMP_README="$(mktemp)"
    cp "${CUSTOM_README}" "${TMP_README}"
fi
if [[ -f "${CUSTOM_REGISTRY_CLIENT}" ]]; then
    TMP_REGISTRY_CLIENT="$(mktemp)"
    cp "${CUSTOM_REGISTRY_CLIENT}" "${TMP_REGISTRY_CLIENT}"
fi
if [[ -f "${CUSTOM_PYPROJECT}" ]]; then
    TMP_PYPROJECT="$(mktemp)"
    cp "${CUSTOM_PYPROJECT}" "${TMP_PYPROJECT}"
fi

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

# Restore custom pyproject.toml if it existed (preserves metadata like license, authors, etc.)
if [[ -n "${TMP_PYPROJECT}" ]]; then
    cp "${TMP_PYPROJECT}" "${CUSTOM_PYPROJECT}"
    rm -f "${TMP_PYPROJECT}"
else
    ROOT_DIR="${ROOT_DIR}" python3 - <<'PY'
import os
from pathlib import Path

pyproject = Path(os.environ["ROOT_DIR"]) / "sdk/python/pyproject.toml"
content = pyproject.read_text()
content = content.replace('readme = "README.md"\n', '')
content = content.replace('include = ["CHANGELOG.md", "a2a_registry_sdk/py.typed"]\n', 'include = ["a2a_registry_sdk/py.typed"]\n')
pyproject.write_text(content)
PY
fi

# Restore custom README
if [[ -n "${TMP_README}" ]]; then
    cp "${TMP_README}" "${CUSTOM_README}"
    rm -f "${TMP_README}"
fi

# Restore custom registry client
if [[ -n "${TMP_REGISTRY_CLIENT}" ]]; then
    cp "${TMP_REGISTRY_CLIENT}" "${CUSTOM_REGISTRY_CLIENT}"
    rm -f "${TMP_REGISTRY_CLIENT}"
fi
