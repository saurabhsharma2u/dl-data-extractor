# Product & Engineering Roadmap

This roadmap lays out a practical path to evolve `dl-data-extractor` from a single-format parser into a production-grade identity-data extraction library.

## Goals

1. **Parser correctness first**: deterministic, spec-aware parsing with observable failure modes.
2. **Stable public contracts**: typed, versioned outputs that are safe for integrators.
3. **Security and privacy by default**: PII-safe logging and operational controls.
4. **Operational confidence**: reproducible CI, fixture-driven testing, and measurable quality gates.
5. **Extensibility**: a plugin-style architecture for additional barcode/document formats.

---

## Current State (Baseline)

- Parser supports PDF417 payload extraction with canonical + optional alias keys.
- API has a format selector (`DLExtractor::parse(..., $type, $options)`) and currently supports `pdf417`.
- Tests cover representative paths, and CI executes install/validate/test/lint.

This is a strong baseline but still primarily heuristic and tightly centered around one input style.

---

## Roadmap by Horizon

## Horizon 1 (0–4 weeks): Correctness + API hardening

### 1.1 Build a spec-aware parser core

**Outcome:** predictable extraction behavior across compact and delimited payload variants.

- Introduce a parser pipeline with clear stages:
  1) normalization,
  2) tokenization,
  3) field mapping,
  4) validation,
  5) output shaping.
- Introduce parser diagnostics (`warnings`, `unknownCodes`, `droppedFields`, `confidence`).
- Add explicit parse error classes:
  - `MalformedPayloadException`
  - `UnsupportedVersionException`
  - `FieldCollisionException`
- Add deterministic behavior for duplicate tokens (first-wins/last-wins configurable).

**Deliverables**
- `ParserResult` object with `data`, `meta`, and `errors`.
- Tokenizer unit tests for compact and whitespace/newline-delimited samples.
- Backward-compatible array/json adapters.

### 1.2 Stabilize public contracts

**Outcome:** safer integrations and clearer expectations for downstream systems.

- Add typed DTOs (`LicenseRecord`, `PersonName`, `Address`, `DocumentMetadata`).
- Keep existing array output for compatibility, but document DTO output as preferred API.
- Version output contracts (`schemaVersion: v1`).
- Clearly document canonical-vs-alias behavior and deprecation policy.

**Deliverables**
- `toDto()` API path.
- `docs/schema/v1.md` contract documentation.
- Migration notes from associative array output to DTOs.

### 1.3 Quality gate tightening

**Outcome:** fewer regressions and faster confidence checks.

- Add fixture corpus (`tests/Fixtures/*`) with expected outputs (golden files).
- Add data-provider tests across multiple jurisdictions.
- Add minimum coverage gate for parser core.

**Deliverables**
- 25+ fixture-based parser tests.
- CI threshold checks for parser package coverage.

---

## Horizon 2 (1–2 months): Security + operability

### 2.1 PII safety and secure defaults

**Outcome:** safer production usage in real environments.

- Add redaction utilities for logs (`maskName`, `maskAddress`, `maskIdNumber`).
- Add logger integration hooks that default to redacted fields.
- Add `SECURITY.md` and explicit PII guidance.
- Add immutable `PrivacyMode` options (`strict`, `balanced`, `off`).

**Deliverables**
- Redaction helpers and test coverage.
- Security documentation and disclosure policy.
- Examples showing safe logging patterns.

### 2.2 Operational observability

**Outcome:** better issue triage and faster production troubleshooting.

- Add parse trace mode:
  - token boundaries,
  - matched codes,
  - normalization transformations.
- Add performance benchmarking for large batches.
- Add telemetry hooks/callbacks for parse lifecycle events.

**Deliverables**
- `debugTrace` mode.
- Benchmark report for 1k/10k payload runs.
- Basic parse-time metrics output.

### 2.3 Deterministic dependency and CI strategy

**Outcome:** less CI flakiness and reproducible builds.

- Commit and maintain a lockfile strategy appropriate for library CI.
- Add periodic dependency update workflow.
- Add CI jobs for:
  - lowest dependency set,
  - highest dependency set,
  - static analysis strict profile.

**Deliverables**
- Extended CI matrix.
- Weekly dependency audit/update automation.

---

## Horizon 3 (2–4 months): Extensibility + ecosystem

### 3.1 Multi-format plugin architecture

**Outcome:** support additional document/barcode ecosystems cleanly.

- Add transformer registry/provider abstraction.
- Introduce plugin interface for external format packs.
- Implement at least one additional format plugin (pilot target selected by demand).

**Deliverables**
- Plugin SDK docs.
- One non-PDF417 prototype transformer.

### 3.2 Ingestion adapters

**Outcome:** easier real-world integration from scanners and image pipelines.

- Add input adapters for:
  - raw barcode text,
  - decoder SDK output,
  - batched records.
- Define `InputEnvelope` with provenance metadata (timestamp/source confidence/device).

**Deliverables**
- Adapter contracts and examples.
- Batch parsing API with per-record status.

### 3.3 Developer tooling

**Outcome:** easier local debugging and adoption.

- Ship a CLI (`bin/dl-extract`) for parse/testing workflows.
- Add recipe-style examples for frameworks (Laravel/Symfony/plain PHP).
- Add API docs generation and publishing.

**Deliverables**
- CLI help + sample commands.
- Cookbook docs.

---

## Backward Compatibility Policy

- Maintain existing `toArray()` and `toJson()` behavior through `v1.x`.
- Introduce new capabilities behind additive APIs/options.
- Mark legacy alias-heavy behavior as deprecated only with:
  1) clear warning in docs,
  2) one minor cycle overlap,
  3) migration examples.

---

## Proposed Milestones

### Milestone A — “Parser Foundation”
- Spec-aware pipeline
- Diagnostics and parser errors
- Fixture corpus v1

### Milestone B — “Safe Production Use”
- Privacy modes + redaction
- Trace mode + metrics
- Hardened CI matrix

### Milestone C — “Platform Expansion”
- Plugin SDK
- Additional format pilot
- CLI + ecosystem docs

---

## Success Metrics

1. **Correctness**
   - >99% pass rate on maintained fixture corpus.
   - Zero critical parser regressions in two consecutive releases.

2. **Stability**
   - No breaking API changes in `v1.x` without migration path.
   - Documented schema contract coverage for all canonical fields.

3. **Security/Privacy**
   - 100% of logs in examples/tests use redacted output paths.
   - Security policy + disclosure process adopted.

4. **Developer Experience**
   - CI green rate >95% over rolling 30 days.
   - Setup-to-first-parse time under 5 minutes from README instructions.

---

## Open Decisions

1. Should duplicate field handling default to `first-wins` or `last-wins`?
2. Which additional format should be first after PDF417 (based on user demand)?
3. Should lockfile be committed for this library repo or only for CI snapshots?
4. What minimum static analysis strictness is acceptable for near-term adoption?

---

## Immediate Next Actions (next PRs)

1. Add `ParserResult` + parser diagnostics shape.
2. Add fixture corpus and golden tests.
3. Add `SECURITY.md` and redaction helpers.
4. Add DTO output (`toDto()`) while preserving current API.
5. Expand CI with dependency strategy and stricter quality gates.
