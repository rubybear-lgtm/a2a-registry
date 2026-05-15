#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
SPEC_PATH="${ROOT_DIR}/sdk/openapi/a2a-registry.v1.json"
OUTPUT_PATH="${ROOT_DIR}/sdk/npm/src"

rm -rf "${OUTPUT_PATH}" "${ROOT_DIR}/sdk/npm/dist"

npx --yes openapi-typescript-codegen@0.30.0 \
    --input "${SPEC_PATH}" \
    --output "${OUTPUT_PATH}" \
    --client fetch \
    --useOptions \
    --useUnionTypes

npx --yes tsc -p "${ROOT_DIR}/sdk/npm/tsconfig.json"
