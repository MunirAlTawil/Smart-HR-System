#Munir Holiday Itinerary Project— Tourism POI Data Engineering & Analytics Platform

# 1. Project Overview
This project is a **containerized data engineering platform** that collects, processes, stores, and serves **tourism Points of Interest (POIs)** for itinerary-oriented analytics and exploration.

- **Problem addressed**: raw tourism POI data is large, heterogeneous (JSON-LD), and not immediately usable for analytics, mapping, and itinerary building. The project converts this data into queryable models and exposes it through an API and dashboard.
- **Why it is relevant to Holiday/Tourism/Itinerary contexts**: POIs (museums, monuments, restaurants, hotels, etc.) are the building blocks of trip planning. The system enables:
  - Discovering POIs and their attributes
  - Mapping POIs with GeoJSON output
  - Producing simple and hybrid (PostgreSQL + Neo4j) itineraries with type-diversity heuristics
  - Tracking ETL runs and data quality indicators
- **High-level behavior**: the platform extracts POIs from the **DataTourisme API**, transforms them into a normalized structure, loads them into **PostgreSQL**, optionally synchronizes them into **Neo4j**, then provides:
  - A **FastAPI** REST API (`src/api/main.py`)
  - A multi-page **Streamlit** dashboard (`src/dashboard/app.py`)
  - An automated **hourly batch ETL** via a Cron scheduler container (`Dockerfile.scheduler`, `docker/cron/crontab`)

# 2. Project Objectives
From an academic and technical perspective, the project aims to:

- Design and implement a **reproducible ETL pipeline** with robust engineering practices (rate limiting, retries, validation, logging).
- Model tourism POIs in a **relational database** (PostgreSQL) optimized for analytics queries and geospatial filtering (indexes, constraints).
- Replicate POIs into a **graph database** (Neo4j) to support relationship-centric queries and diversity-aware itinerary logic.
- Provide a **consumption layer** composed of:
  - A documented REST API (FastAPI + OpenAPI)
  - An interactive dashboard (Streamlit) with charts and map exploration
- Demonstrate **automation and operationalization** using Docker Compose and a scheduler (Cron), including run tracking.
- Validate correctness via a **test suite** spanning unit tests and integration tests with real database containers.

# 3. Main Features
Only features that are implemented in the current codebase are listed below.

- **Batch ETL pipeline (hourly-capable)**:
  - Extract from DataTourisme (`GET /v1/catalog`) with rate limiting + retry (`src/pipelines/batch_etl.py`)
  - Transform/normalize POIs (coordinates, multilingual labels/descriptions, timestamps; plus city/department/theme extraction)
  - Load into PostgreSQL with **smart UPSERT** (update only when incoming `last_update` is newer)
  - Track ETL executions in `pipeline_runs` (created on demand by the ETL code)
- **Relational storage in PostgreSQL 16**:
  - Core `poi` table (SQLAlchemy model in `src/api/models.py`)
  - Initial schema bootstrapping via `sql/init.sql`
  - Additional idempotent migrations under `sql/migrations/` and `sql/02_schema_migration.sql`
- **Neo4j graph loading (Neo4j 5.15 Community)**:
  - Loads POIs from PostgreSQL into Neo4j (`src/pipelines/graph_loader.py`)
  - Creates constraints/indexes and builds relationships: `HAS_TYPE`, `IN_CITY`, `IN_DEPARTMENT`
  - CLI entry point `python -m src.pipelines.run_graph_load`
- **FastAPI REST API** for POI retrieval, analytics, ETL status, graph operations, and itinerary generation (`src/api/main.py`)
- **Streamlit dashboard** (multi-page) providing KPIs, charts, quality metrics, exploration, mapping, itinerary UI, and graph stats (`src/dashboard/app.py`)
- **Automation/Scheduling**:
  - Hourly Cron job in `holiday_scheduler` runs ETL then graph load (`docker/cron/crontab`, `Dockerfile.scheduler`)
- **Testing**:
  - Unit tests for API endpoints and transformation logic (SQLite-based)
  - Integration tests using `testcontainers` for PostgreSQL and Neo4j

# 4. System Architecture
The system is orchestrated with **Docker Compose** (`docker-compose.yml`) and consists of five key services:

- **PostgreSQL** (`postgres:16`) as the primary relational store
- **Neo4j** (`neo4j:5.15-community`) as the graph store
- **FastAPI API** (`Dockerfile.api`) to expose REST endpoints and trigger/inspect pipeline state
- **Streamlit dashboard** (`Dockerfile.dashboard`) to visualize and interact with the data
- **Scheduler** (`Dockerfile.scheduler`) running Cron hourly to execute ETL and graph synchronization

### Communication paths (real runtime behavior)
- The **ETL pipeline** pulls data from **DataTourisme API** (external) and writes to **PostgreSQL**.
- The **graph loader** reads from **PostgreSQL** and writes to **Neo4j**.
- The **FastAPI service** reads from **PostgreSQL** for most endpoints and talks to **Neo4j** for graph endpoints and hybrid itinerary health checks.
- The **Streamlit dashboard** calls the **FastAPI service** over HTTP (`API_BASE_URL` env var).

### ASCII architecture diagram

```text
                (external)
        DataTourisme REST API (JSON-LD)
                   |
                   |  Extract (rate-limited, retry)
                   v
        ETL: src/pipelines/batch_etl.py
      (Transform + Smart UPSERT + run tracking)
                   |
                   v
            PostgreSQL 16 (holiday)
                   |
                   | Graph load (MERGE + constraints)
                   v
              Neo4j 5.15 (graph)
                   ^
                   | graph stats / hybrid itinerary support
                   |
     FastAPI (src/api/main.py)  <----  Streamlit (src/dashboard/app.py)
             REST + OpenAPI              Dashboard (HTTP calls to API)
```

### Scheduler / Cron
Scheduling is implemented with **Cron** (not Airflow). The container `holiday_scheduler`:
- Writes relevant environment variables into `/app/.env.cron` at startup (so Cron jobs have them).
- Runs `/app/run_etl.sh` **every hour** (`0 * * * *`), which executes:
  - `python3 -m src.pipelines.batch_etl --limit-per-run 500 --max-pages 5 --since-hours 24`
  - `python3 -m src.pipelines.run_graph_load --batch-size 100`

# 5. Technologies Used
The following technologies are present in the codebase and/or runtime configuration.

| Category | Technology | Purpose |
|---|---|---|
| Language | Python 3.11 | Implementation language across services |
| API | FastAPI | REST API + OpenAPI docs (`src/api/main.py`) |
| ASGI Server | Uvicorn | Runs FastAPI in container and locally |
| Validation | Pydantic v2 | Request/response models for API |
| ORM | SQLAlchemy | PostgreSQL access and models (`src/api/models.py`) |
| Relational DB | PostgreSQL 16 | Primary storage for POIs (`docker-compose.yml`) |
| Graph DB | Neo4j 5.15 Community | Graph model + diversity-related queries (`src/pipelines/graph_loader.py`) |
| Dashboard | Streamlit | Interactive visualization + forms (`src/dashboard/app.py`) |
| Mapping | Folium + streamlit-folium | Map Explorer and itinerary map rendering |
| Analytics | pandas, Plotly (installed) | Data shaping and plotting in dashboard (Plotly is installed even if not used everywhere) |
| Extraction | requests | HTTP extraction from DataTourisme |
| Configuration | python-dotenv | `.env` loading for local/containers |
| Containerization | Docker | Build isolated services (API/Dashboard/Scheduler) |
| Orchestration | Docker Compose | Multi-service deployment (`docker-compose.yml`) |
| Testing | pytest | Test runner (`pytest.ini`) |
| Integration Testing | testcontainers (postgres, neo4j) | Real DB containers for integration tests |
| Linting | flake8 | Static lint checks (`.flake8`) |

# 6. Project Structure
Key paths in the repository (real structure):

```text
.
├─ docker-compose.yml
├─ Dockerfile.api
├─ Dockerfile.dashboard
├─ Dockerfile.scheduler
├─ requirements.txt
├─ .env.example
├─ scripts/
│  ├─ run_db.ps1
│  ├─ run_api.ps1
│  └─ run_dashboard.ps1
├─ docker/
│  ├─ api-entrypoint.sh
│  └─ cron/crontab
├─ sql/
│  ├─ init.sql
│  ├─ schema.sql
│  ├─ 02_schema_migration.sql
│  └─ migrations/
│     ├─ 001_add_poi_type.sql
│     ├─ 002_add_poi_fields_and_etl_run.sql
│     ├─ 003_add_missing_poi_columns.sql
│     └─ 004_add_theme_column.sql
├─ src/
│  ├─ api/ (FastAPI app + DB session + SQLAlchemy models)
│  ├─ analytics/ (analytics + itinerary algorithms)
│  ├─ dashboard/ (Streamlit dashboard app)
│  ├─ extract/ (standalone extractor saving raw JSON + CSV)
│  ├─ pipelines/ (production ETL + graph loading)
│  └─ config.py (paths + env loading)
├─ pipelines/ (a second, simpler pipeline implementation; used by docker-compose manual profile)
│  └─ batch/ (fetch/transform/load orchestrator for manual execution)
├─ data/
│  ├─ raw/ (example raw API response files)
│  └─ processed/ (example processed CSV)
├─ tests/ (unit + integration tests)
└─ notebooks/ (exploration notebook)
```

Important note on ETL code paths:
- The **scheduler** and the **API-triggered ETL** run the ETL module at `src/pipelines/batch_etl.py`.
- Docker Compose also includes a **manual** service `batch_pipeline` that runs `pipelines/batch/run_pipeline.py` (a separate pipeline implementation).

# 7. Data Source
### DataTourisme API (France)
The external data source is the **DataTourisme API**, a French tourism data platform providing POIs as JSON-LD.

- **Base URL**: `https://api.datatourisme.fr`
- **Endpoint used**: `GET /v1/catalog`
- **Authentication**: API key via header `X-API-Key` (configured as `DATATOURISME_API_KEY`)
- **Fields requested by ETL**: `uuid,label,type,uri,isLocatedAt,hasDescription,lastUpdate` (see `src/pipelines/batch_etl.py` and `src/extract/fetch_datatourisme.py`)

The repository includes a dedicated academic note with examples and response format:
- `docs/data_sources.md`

# 8. ETL Pipeline
The primary ETL implementation used for automation and API triggering is `src/pipelines/batch_etl.py`.

### Extraction
- Fetches POIs from DataTourisme with pagination and limits:
  - `page_size` capped at 250
  - `limit_per_run` and `max_pages` CLI parameters
- Implements:
  - **Rate limiting** (`MAX_REQUESTS_PER_SECOND=10`, `MAX_REQUESTS_PER_HOUR=1000`) via `RateLimiter`
  - **Retry with exponential backoff** via `fetch_with_retry()`
- Produces a list of raw POI dictionaries (`objects`) for processing.

### Transformation
Transformation converts raw JSON-LD POI objects into a normalized dictionary aligned with the `poi` table:

- **Required**: `id` (uuid), `latitude`, `longitude` (POIs without coordinates are skipped)
- **Normalized fields**: `label`, `description`, `uri`, `type`, `last_update`
- **Derived fields**:
  - `city` from `isLocatedAt → schema:address → schema:addressLocality`
  - `department_code` from the first two digits of the French postal code
  - `theme` heuristically extracted from the URI path (`extract_theme_from_uri()`)
- Stores `raw_json` as an audit trail (JSON string inserted as JSONB).

### Loading (PostgreSQL)
Loading uses a **smart UPSERT**:
- Inserts new POIs.
- Updates existing POIs **only if** incoming `last_update` is newer than the stored value (or stored is NULL).

### Graph loading (Neo4j)
Graph loading is implemented in `src/pipelines/graph_loader.py` and is executed:
- By the scheduler (`/app/run_etl.sh`) after each ETL batch
- Or manually via CLI (`python -m src.pipelines.run_graph_load`)
- Or via API (`POST /graph/sync`)

# 9. Database Design
## Relational database (PostgreSQL)
### Core table: `poi`
The API and ETL operate on the SQLAlchemy model `POI` (`src/api/models.py`), which maps to `poi`.

Key fields stored:
- **Identifiers/metadata**: `id` (PK), `uri`, `source_id`, `created_at`, `last_update`
- **Content**: `label`, `description`, `type`, `theme`
- **Geospatial**: `latitude`, `longitude`
- **Location enrichment**: `city`, `department_code`
- **Audit trail**: `raw_json` (JSON/JSONB in DB)

Why a relational model:
- Efficient aggregations (counts per type/day)
- Filtering/search (type filter, text search via ILIKE; schema also defines a GIN index for full-text search)
- Simple and reliable transactional loading with constraints on coordinate validity

### Schema initialization and migrations (important)
- Initial DB objects are created by PostgreSQL at container init from `sql/init.sql` (mounted in `docker-compose.yml`).
- The API container runs `sql/02_schema_migration.sql` at startup (see `docker/api-entrypoint.sh`). This migration ensures `city` and `department_code` exist.
- Additional idempotent migrations exist under `sql/migrations/`, including `004_add_theme_column.sql` (required if you want the ETL to persist the derived `theme` field).

## Graph database (Neo4j)
Graph model implemented by `src/pipelines/graph_loader.py`:

### Nodes
- `(:POI {id, label, type, latitude, longitude, uri, theme, last_update})`
- `(:Type {name})`
- `(:City {name})`
- `(:Department {code})`

### Relationships
- `(:POI)-[:HAS_TYPE]->(:Type)`
- `(:POI)-[:IN_CITY]->(:City)`
- `(:POI)-[:IN_DEPARTMENT]->(:Department)`

Why Neo4j was added:
- Supports relationship-centric exploration and is directly used as an auxiliary source for **type diversity scoring** in the hybrid itinerary algorithm (`src/analytics/itinerary_hybrid.py`).

# 10. API Endpoints
All endpoints below are defined in `src/api/main.py` (no additional endpoint files are used).

| Endpoint | Method | Purpose |
|---|---:|---|
| `/` | GET | Root message and API version |
| `/health` | GET | Health check + PostgreSQL connectivity check |
| `/pois` | GET | Paginated POI listing with optional `search` and `type` filtering |
| `/pois/geojson` | GET | GeoJSON `FeatureCollection` of POIs (supports `bbox`, `search`, `type`, pagination) |
| `/pois/{poi_id}` | GET | Retrieve a single POI by ID |
| `/pois/recent` | GET | Retrieve recent POIs ordered by `last_update` |
| `/stats` | GET | Global POI statistics (counts, distinct types, update range) |
| `/stats/categories` | GET | Counts grouped by category (joins `category`/`poi_category` when populated) |
| `/stats/coordinates` | GET | Coordinates list for mapping |
| `/charts/types` | GET | Type distribution for charts |
| `/charts/updates` | GET | Updates per day (time-series) |
| `/quality` | GET | NULL/missing-field counts for existing columns |
| `/etl/status` | GET | Alias to last pipeline run status (reads `pipeline_runs`) |
| `/pipeline/last-run` | GET | Last pipeline run status (reads `pipeline_runs`) |
| `/etl/run-now` | POST | Launch ETL in the background (runs `python -m src.pipelines.batch_etl`) |
| `/graph/summary` | GET | Neo4j graph node/relationship counts |
| `/graph/sync` | POST | Sync POIs from PostgreSQL to Neo4j (optional token via `GRAPH_SYNC_TOKEN`) |
| `/itinerary` | GET | Generate itinerary using PostgreSQL-based greedy algorithm |
| `/itinerary/build` | POST | Generate itinerary using hybrid approach (PostgreSQL + Neo4j diversity scoring) |
| `/itinerary/health` | GET | Itinerary-related counts from PostgreSQL and Neo4j availability check |

Access the interactive API documentation after startup:
- **Swagger UI**: `http://localhost:8000/docs`

# 11. Dashboard Pages
The Streamlit dashboard (`src/dashboard/app.py`) implements the following pages:

- **Overview**: KPIs from `/stats` (total POIs, coordinates coverage, distinct types, update range).
- **Types Chart**: bar chart driven by `/charts/types`.
- **Updates Chart**: line chart driven by `/charts/updates` (configurable day range).
- **Data Quality**: missing-field counts from `/quality` and completeness calculations.
- **POI Explorer**: paginated browsing using `/pois` with filters.
- **Map Explorer**: map visualization using `/pois/geojson`, optional bounding-box filtering.
- **Itinerary Builder**: form-based hybrid itinerary generator calling `POST /itinerary/build`, plus map rendering.
- **Graph**: Neo4j summary statistics via `/graph/summary` and a short model explanation.

# 12. Automation / Scheduling
Scheduling is implemented with **Cron** inside the `holiday_scheduler` container:

- **Cron file**: `docker/cron/crontab`
- **Schedule**: `0 * * * *` (hourly, at minute 0)
- **Job**: runs `/app/run_etl.sh` and appends output to `/var/log/cron.log`
- **What it runs** (in order):
  1. `python3 -m src.pipelines.batch_etl ...`
  2. `python3 -m src.pipelines.run_graph_load ...`

This choice is explicit and appropriate for a university project: Cron is simpler than Airflow while still demonstrating automation, reproducibility, and observability via logs and run tracking.

# 13. How to Run the Project
## Prerequisites
- **Docker Desktop** (with Docker Compose v2)
- **Python 3.11** (optional, for running services without Docker)
- A **DataTourisme API key** (required to run the real ETL against the live API)

## 13.1 Configure environment variables
1. Copy `.env.example` to `.env`
2. Set:
   - `DATATOURISME_API_KEY=...`
3. (Optional) Neo4j credentials can be overridden via environment variables used in `docker-compose.yml`:
   - `NEO4J_URI`, `NEO4J_USER`, `NEO4J_PASSWORD`

## 13.2 Start all services with Docker Compose
From the project root:

```bash
docker compose up -d
```

## 13.3 Verify services are running
```bash
docker compose ps
```

## 13.4 Access the applications
- **FastAPI**:
  - Root: `http://localhost:8000/`
  - Swagger UI: `http://localhost:8000/docs`
  - Health: `http://localhost:8000/health`
- **Streamlit dashboard**: `http://localhost:8501`
- **Neo4j Browser**: `http://localhost:7474`
  - Bolt: `bolt://localhost:7687`

## 13.5 Load data (choose one)
### Option A — Wait for the hourly scheduler
The `holiday_scheduler` will run automatically every hour and populate PostgreSQL, then Neo4j.

### Option B — Trigger ETL from the API
```bash
curl -X POST "http://localhost:8000/etl/run-now?limit_per_run=500&max_pages=5"
```
Then check latest run:
```bash
curl "http://localhost:8000/pipeline/last-run"
```

### Option C — Run the scheduler job immediately (inside the scheduler container)
```bash
docker compose exec holiday_scheduler bash -lc "/app/run_etl.sh"
```

### Option D — Run the manual batch pipeline service (separate implementation)
This uses the Compose profile `manual` and runs `pipelines/batch/run_pipeline.py`:
```bash
docker compose --profile manual run --rm batch_pipeline
```

## 13.6 Apply full migrations (recommended for complete schema)
The API auto-runs only `sql/02_schema_migration.sql` on startup. To apply the additional migrations (including `theme`), run:

```powershell
.\run_migrations.ps1
```

# 14. Useful Commands
## Docker Compose lifecycle
- **Start**:

```bash
docker compose up -d
```

- **Stop**:

```bash
docker compose down
```

- **Restart one service** (example: API):

```bash
docker compose restart api
```

- **View logs**:

```bash
docker compose logs -f api
docker compose logs -f holiday_scheduler
```

## Run ETL and graph load manually
- **Run ETL + graph load (scheduler script)**:

```bash
docker compose exec holiday_scheduler bash -lc "/app/run_etl.sh"
```

- **Run graph load only**:

```bash
docker compose exec holiday_scheduler python -m src.pipelines.run_graph_load --batch-size 100 --summary
```

## Quick data checks
- **Count POIs**:

```bash
docker compose exec postgres psql -U holiday -d holiday -c "SELECT COUNT(*) FROM poi;"
```

- **Count by type (top 10)**:

```bash
docker compose exec postgres psql -U holiday -d holiday -c "SELECT type, COUNT(*) FROM poi GROUP BY type ORDER BY COUNT(*) DESC LIMIT 10;"
```

## Local (non-Docker) developer scripts (Windows)
- **Start API**: `.\scripts\run_api.ps1`
- **Start dashboard**: `.\scripts\run_dashboard.ps1`
- **Start Docker services**: `.\scripts\run_db.ps1`

# 15. Testing
Testing is implemented with `pytest` and configured in `pytest.ini` with markers:
- `unit`: tests that do not require external services
- `integration`: tests that require Docker containers (PostgreSQL/Neo4j) via `testcontainers`

## Test files (real)
- **Unit**:
  - `tests/test_api_endpoints.py`: FastAPI endpoint tests using SQLite and dependency overrides
  - `tests/test_transformation_logic.py`: unit tests for ETL transformation helpers (coordinates, city/department, theme extraction, timestamp parsing)
  - `tests/test_geojson_endpoint.py`: GeoJSON endpoint behavior and bbox parsing
- **Integration**:
  - `tests/test_etl_pipeline_integration.py`: end-to-end ETL using a PostgreSQL test container and mocked API responses
  - `tests/test_neo4j_graph_loading.py`: Neo4j load tests using PostgreSQL + Neo4j test containers

## How to run tests
- **All tests**:

```bash
pytest -v
```

- **Unit tests only**:

```bash
pytest -m unit -v
```

- **Integration tests only** (requires Docker running on the host):

```bash
pytest -m integration -v
```

# 16. Strengths of the Project
- **End-to-end data engineering scope**: extraction → transformation → relational storage → graph replication → API → dashboard.
- **Operational realism**: containerization with health checks (`docker-compose.yml`), automated migration step for the API (`docker/api-entrypoint.sh`), and scheduled execution via Cron.
- **Idempotency and safety**:
  - PostgreSQL UPSERT strategy avoids overwriting with stale updates (`src/pipelines/batch_etl.py`)
  - Neo4j MERGE-based loading supports safe re-runs (`src/pipelines/graph_loader.py`)
- **Test strategy that matches real systems**: unit tests with lightweight SQLite plus integration tests using real Postgres/Neo4j containers.
- **Academic clarity**: explicit separation of concerns (pipelines vs API vs dashboard) and traceable artifacts (SQL schema, migrations, docs).

# 17. Limitations and Future Improvements
This section is intentionally honest and aligned with what exists in the repository.

- **Migrations are partially automatic**:
  - The API auto-runs only `sql/02_schema_migration.sql` (adds `city`, `department_code`).
  - Other migrations (including `theme`) require manual execution (`run_migrations.ps1`) or an extended automated migration step.
- **Two pipeline implementations exist**:
  - `src/pipelines/batch_etl.py` is used by the scheduler and API-triggered ETL.
  - `pipelines/batch/run_pipeline.py` is used by the Compose manual profile service.
  - A production hardening step would consolidate these into a single pipeline package to reduce duplication.
- **Category tables are defined but not populated by the primary ETL**:
  - `category` and `poi_category` exist in `sql/init.sql`, and `/stats/categories` queries them.
  - The current main ETL loads `poi` records; populating category relations would be a next step.
- **Geospatial logic is approximate**:
  - The itinerary search uses SQL trigonometric distance (Haversine-like) and returns useful results, but production systems might use PostGIS for richer geospatial indexing and accuracy.
- **ETL run triggering from API**:
  - `POST /etl/run-now` spawns a background subprocess; a production approach would use a job queue (e.g., Celery/RQ) and persistent job state.
- **Neo4j use in hybrid itinerary**:
  - Diversity scoring depends on the graph being populated; the system detects Neo4j availability and can fall back when needed, but results may differ if the graph is not synced.

# 18. Conclusion
This Holiday project constitutes a **complete, defensible university-level data engineering system**: it operationalizes a real external tourism API into a dual-storage architecture (PostgreSQL + Neo4j), exposes the data via a documented FastAPI layer, and provides an interactive Streamlit dashboard for exploration, mapping, quality monitoring, and itinerary generation. The implementation demonstrates core competencies expected in data engineering and backend/DevOps practice: ETL robustness, schema design, container orchestration, automation via scheduling, and verification through unit and integration testing.

