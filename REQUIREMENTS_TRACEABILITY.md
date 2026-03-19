# Requirements Traceability Matrix

**Project:** Holiday Itinerary Data Engineering Project  
**Date:** 2026-02-12  
**Auditor:** Senior Data Engineer & Project Auditor

---

## Overview

This document compares the project repository against official requirements (R1-R8) and provides a status assessment, evidence, gaps, and fix plans for each requirement.

---

## R1 Data collection: collect POIs from a source (API/scraping), extract theme from URL, provide CSV + JSON + processing explanation

### Status: **PARTIAL**

### Evidence:
- **POI Collection from API:** ✅
  - `src/pipelines/batch_etl.py` - Main ETL pipeline that fetches from DataTourisme API
  - `src/extract/fetch_datatourisme.py` - Extraction module with API integration
  - `data/raw/datatourisme_catalog_page1.json` - Example raw JSON data
- **CSV Output:** ✅
  - `data/processed/datatourisme_pois.csv` - Processed CSV file
  - `src/extract/fetch_datatourisme.py:convert_to_csv()` - CSV conversion function
- **JSON Output:** ✅
  - `data/raw/datatourisme_catalog_page1.json` - Raw JSON data
  - `src/pipelines/batch_etl.py` - Saves raw JSON during extraction
- **Processing Explanation:** ⚠️ PARTIAL
  - `docs/data_sources.md` - Contains some processing information
  - `src/extract/fetch_datatourisme.py` - Code comments explain transformation
  - Missing: Dedicated processing explanation document
- **Extract Theme from URL:** ❌ MISSING
  - No code found that extracts "theme" from URL
  - POI data has `uri` field but no theme extraction logic

### What is missing:
- Theme extraction functionality from POI URLs/URIs
- Dedicated processing explanation document (separate from data_sources.md)
- Documentation of the complete data processing pipeline (extract → transform → load)

### Fix plan:
1. **Create theme extraction function:**
   - File: `src/pipelines/batch_etl.py`
   - Add function: `extract_theme_from_uri(uri: str) -> Optional[str]`
   - Extract theme/category from DataTourisme URI patterns
   - Add `theme` column to POI model and database schema
2. **Create processing explanation document:**
   - File: `docs/PROCESSING_EXPLANATION.md`
   - Document: Data flow, transformation steps, validation rules, error handling
   - Include: Flow diagrams, example transformations, data quality checks
3. **Update ETL pipeline:**
   - File: `src/pipelines/batch_etl.py`
   - Integrate theme extraction into `transform_poi()` function
   - Add theme to database insert/update operations
4. **Update database schema:**
   - File: `sql/migrations/004_add_theme_column.sql`
   - Add `theme` column to `poi` table
   - Update `src/api/models.py` POI model

---

## R2 Data modeling: relational DB + UML + scripts to create & query

### Status: **PARTIAL**

### Evidence:
- **Relational Database (PostgreSQL):** ✅
  - `sql/schema.sql` - Complete database schema
  - `sql/init.sql` - Initialization script
  - `sql/migrations/` - Migration scripts (001, 002, 003)
  - `src/api/models.py` - SQLAlchemy ORM models
  - `docs/schema.md` - Schema documentation
- **UML Diagram:** ✅
  - `docs/uml.puml` - PlantUML architecture diagram
  - `docs/architecture/architecture.mmd` - Mermaid diagram
  - `docs/architecture/architecture.drawio` - Draw.io diagram
  - `docs/architecture/architecture.png` - PNG diagram
- **Scripts to Create:** ✅
  - `sql/init.sql` - Creates all tables
  - `sql/schema.sql` - Schema definitions
  - `sql/migrations/*.sql` - Incremental migrations
- **Scripts to Query:** ⚠️ PARTIAL
  - Example queries in `README.md` (lines 413-442)
  - Example queries in `docs/GRAPH_MODEL.md` (Cypher queries for Neo4j)
  - Missing: Dedicated SQL query script files for PostgreSQL

### What is missing:
- Dedicated SQL query script files (e.g., `sql/queries/example_queries.sql`)
- Query documentation with examples for common use cases
- ERD (Entity Relationship Diagram) for database schema (UML exists but not ERD)

### Fix plan:
1. **Create SQL query scripts:**
   - File: `sql/queries/example_queries.sql`
   - Include: Common queries (find POIs by type, by city, by coordinates, aggregations)
   - Add comments explaining each query
2. **Create query documentation:**
   - File: `docs/QUERY_EXAMPLES.md`
   - Document: Common query patterns, performance tips, index usage
   - Include: Examples for analytics, filtering, geospatial queries
3. **Create ERD diagram:**
   - File: `docs/erd.puml` or `docs/erd.drawio`
   - Show: Table relationships, foreign keys, cardinality
   - Generate: `docs/erd.png` from PlantUML

---

## R3 Graph DB: Neo4j with geo coordinates + scripts to create & query

### Status: **COMPLETE**

### Evidence:
- **Neo4j Database:** ✅
  - `src/pipelines/graph_loader.py` - Graph loader with node/relationship creation
  - `docker-compose.yml` - Neo4j service configuration
  - `docs/GRAPH_MODEL.md` - Graph model documentation
- **Geo Coordinates:** ✅
  - POI nodes include `latitude` and `longitude` properties
  - `src/pipelines/graph_loader.py:load_pois_to_neo4j()` - Loads coordinates
- **Scripts to Create:** ✅
  - `src/pipelines/graph_loader.py:create_constraints_and_indexes()` - Creates constraints/indexes
  - `src/pipelines/run_graph_load.py` - CLI entry point for graph loading
- **Scripts to Query:** ✅
  - `docs/GRAPH_MODEL.md` - Contains Cypher query examples
  - `README.md` - Contains example Cypher queries (lines 413-442)
  - `src/pipelines/graph_loader.py:get_graph_summary()` - Query function

### What is missing:
- None (requirement fully met)

### Fix plan:
- No fixes needed

---

## R4 Consumption: functions that compute/return data, FastAPI API

### Status: **COMPLETE**

### Evidence:
- **Functions that Compute/Return Data:** ✅
  - `src/analytics/analytics.py` - Analytics functions (counts, aggregations, search)
  - `src/analytics/itinerary.py` - Itinerary generation algorithm
  - Functions: `get_poi_counts_by_category()`, `get_recent_pois()`, `get_coordinates_list()`, etc.
- **FastAPI API:** ✅
  - `src/api/main.py` - FastAPI application with 20+ endpoints
  - Endpoints: `/pois`, `/stats`, `/charts`, `/itinerary`, `/graph/summary`, etc.
  - Auto-generated documentation at `/docs` (Swagger UI)

### What is missing:
- None (requirement fully met)

### Fix plan:
- No fixes needed

---

## R5 Dashboard OR ML

### Status: **COMPLETE** (Dashboard track chosen)

### Evidence:
- **Dashboard Track Selected:** ✅
  - `src/dashboard/app.py` - Streamlit multi-page dashboard
  - Pages: Overview, Types Chart, Updates Chart, Data Quality, POI Explorer, Map Explorer, Itinerary Builder, Graph Statistics
- **analytics.py:** ✅
  - `src/analytics/analytics.py` - Analytics functions
- **stats/charts endpoints:** ✅
  - `src/api/main.py:377` - GET `/stats` endpoint
  - `src/api/main.py:474` - GET `/charts/types` endpoint
  - `src/api/main.py:493` - GET `/charts/updates` endpoint
- **Streamlit App:** ✅
  - `src/dashboard/app.py` - Complete Streamlit application
  - `Dockerfile.dashboard` - Docker image for dashboard
  - `docker-compose.yml` - Dashboard service

### What is missing:
- ML track not implemented (but Dashboard track is complete, so this is acceptable)

### Fix plan:
- No fixes needed (Dashboard track fully satisfies requirement)

---

## R6 Automation: batch pipeline scheduled (Airflow/Jenkins/Cron). Optional streaming (Kafka or simulated websocket)

### Status: **PARTIAL**

### Evidence:
- **Batch Pipeline:** ✅
  - `src/pipelines/batch_etl.py` - Complete ETL pipeline
  - `src/pipelines/run_graph_load.py` - Graph loader pipeline
- **Scheduled (Cron):** ✅
  - `docker/cron/crontab` - Cron configuration (hourly: `0 * * * *`)
  - `Dockerfile.scheduler` - Scheduler container with cron daemon
  - `docker-compose.yml` - `holiday_scheduler` service
- **Streaming (Optional):** ❌ MISSING
  - No Kafka implementation
  - No simulated websocket implementation
  - No streaming pipeline code

### What is missing:
- Streaming pipeline (Kafka or websocket simulation)
- Real-time data processing capability
- WebSocket server for live updates

### Fix plan:
1. **Implement optional streaming (if required):**
   - File: `src/pipelines/streaming.py` (new)
   - Option A: Kafka integration
     - Add Kafka producer to ETL pipeline
     - Add Kafka consumer for real-time processing
     - File: `docker-compose.yml` - Add Kafka service
   - Option B: WebSocket simulation
     - File: `src/api/websocket.py` (new)
     - Add WebSocket endpoint to FastAPI
     - Simulate streaming by pushing updates via WebSocket
2. **Update docker-compose.yml:**
   - Add Kafka service (if Kafka chosen)
   - Add WebSocket configuration (if WebSocket chosen)
3. **Documentation:**
   - File: `docs/STREAMING.md`
   - Document: Streaming architecture, setup, usage

---

## R7 Deployment: unit tests, CI/CD (ci.yaml all branches; release.yaml master), docker images, docker-compose with DB/API/STREAMLIT/(AIRFLOW or KAFKA)

### Status: **COMPLETE**

### Evidence:
- **Unit Tests:** ✅
  - `tests/test_api_endpoints.py` - API endpoint tests
  - `tests/test_etl_pipeline_integration.py` - ETL integration tests
  - `tests/test_transformation_logic.py` - Transformation logic tests
  - `tests/test_neo4j_graph_loading.py` - Neo4j graph loading tests
  - `tests/test_geojson_endpoint.py` - GeoJSON endpoint tests
  - `pytest.ini` - Pytest configuration
- **CI/CD (ci.yaml all branches):** ✅
  - `.github/workflows/ci.yaml` - CI workflow
  - Triggers: Push to all branches, pull requests
  - Steps: Lint (flake8), test (pytest), build Docker images
- **CI/CD (release.yaml master):** ✅
  - `.github/workflows/release.yaml` - Release workflow
  - Triggers: Push to `main` branch, version tags (`v*.*.*`)
  - Steps: Lint, test, build, push Docker images to DockerHub
- **Docker Images:** ✅
  - `Dockerfile.api` - FastAPI service image
  - `Dockerfile.dashboard` - Streamlit dashboard image
  - `Dockerfile.scheduler` - Cron scheduler image
- **Docker Compose:** ✅
  - `docker-compose.yml` - Complete orchestration
  - Services: `postgres` (DB), `neo4j` (Graph DB), `api` (FastAPI), `dashboard` (Streamlit), `holiday_scheduler` (Cron)
  - Health checks, dependencies, volumes configured

### What is missing:
- Airflow not implemented (but Cron is used, which satisfies the requirement)
- Kafka not implemented (optional, see R6)

### Fix plan:
- No fixes needed (Cron satisfies scheduling requirement; Kafka is optional)

---

## R8 Documentation & defense readiness: strong README with illustrations; final report structure; demo-ready app

### Status: **PARTIAL**

### Evidence:
- **Strong README:** ✅
  - `README.md` - Comprehensive README (831+ lines)
  - Includes: Setup instructions, API documentation, usage examples, troubleshooting
- **Illustrations:** ✅
  - `docs/architecture/architecture.png` - Architecture diagram (PNG)
  - `docs/architecture/architecture.mmd` - Mermaid diagram
  - `docs/architecture/architecture.drawio` - Draw.io diagram
  - `docs/uml.puml` - PlantUML diagram
- **Final Report Structure:** ⚠️ PARTIAL
  - `PROJECT_AUDIT.md` - Comprehensive audit report
  - `docs/AUDIT_REPORT.md` - Audit report
  - `docs/FINAL_AUDIT_REPORT.md` - Final audit report
  - Missing: Structured final report with sections: Introduction, Methodology, Architecture, Results, Conclusion
- **Demo-Ready App:** ✅
  - `src/dashboard/app.py` - Fully functional Streamlit dashboard
  - `src/api/main.py` - Production-ready FastAPI API
  - `docker-compose.yml` - One-command deployment
  - All services containerized and working

### What is missing:
- Structured final report document with required sections
- Defense presentation materials (slides, demo script)
- Performance benchmarks and results documentation

### Fix plan:
1. **Create final report document:**
   - File: `FINAL_REPORT.md` or `docs/FINAL_REPORT.md`
   - Sections:
     - Introduction (project overview, objectives)
     - Methodology (data collection, processing, architecture decisions)
     - Architecture (system design, components, data flow)
     - Implementation (technologies, challenges, solutions)
     - Results (data statistics, performance metrics, screenshots)
     - Conclusion (achievements, limitations, future work)
   - Include: Diagrams, code snippets, performance metrics
2. **Create defense presentation:**
   - File: `docs/DEFENSE_PRESENTATION.md` or `presentation/defense_slides.md`
   - Sections: Project overview, architecture, demo, Q&A preparation
3. **Create demo script:**
   - File: `docs/DEMO_SCRIPT.md`
   - Step-by-step demo instructions
   - Key features to showcase
   - Expected outputs
4. **Add performance benchmarks:**
   - File: `docs/PERFORMANCE_BENCHMARKS.md`
   - Document: API response times, ETL processing times, database query performance
   - Include: Load testing results, scalability metrics

---

## Summary

| Requirement | Status | Completion % |
|------------|--------|--------------|
| R1 Data collection | PARTIAL | 75% |
| R2 Data modeling | PARTIAL | 85% |
| R3 Graph DB | COMPLETE | 100% |
| R4 Consumption | COMPLETE | 100% |
| R5 Dashboard OR ML | COMPLETE | 100% |
| R6 Automation | PARTIAL | 80% |
| R7 Deployment | COMPLETE | 100% |
| R8 Documentation | PARTIAL | 80% |

**Overall Project Completion: 90%**

### Critical Gaps (Must Fix):
1. **R1:** Theme extraction from URL
2. **R1:** Processing explanation document
3. **R2:** SQL query script files
4. **R8:** Structured final report

### Important Gaps (Should Fix):
1. **R2:** ERD diagram for database schema
2. **R6:** Optional streaming implementation (if required)
3. **R8:** Defense presentation materials

### Minor Gaps (Nice to Have):
1. **R1:** Enhanced processing documentation
2. **R2:** Query documentation with examples
3. **R8:** Performance benchmarks document

---

## Next Steps

1. **Priority 1 (Critical):**
   - Implement theme extraction (R1)
   - Create processing explanation document (R1)
   - Create SQL query scripts (R2)
   - Create structured final report (R8)

2. **Priority 2 (Important):**
   - Create ERD diagram (R2)
   - Implement optional streaming if required (R6)
   - Create defense presentation (R8)

3. **Priority 3 (Enhancement):**
   - Enhance documentation
   - Add performance benchmarks
   - Create demo script

---

*End of Requirements Traceability Matrix*

