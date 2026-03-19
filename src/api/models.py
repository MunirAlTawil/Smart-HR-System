"""
SQLAlchemy models for database tables.
"""
from sqlalchemy import Column, Text, Float, Integer, DateTime
from sqlalchemy.sql import func
from sqlalchemy.types import TypeDecorator, JSON
from sqlalchemy.dialects.postgresql import JSONB
from src.api.db import Base


class JSONBCompat(TypeDecorator):
    """
    JSON column that uses JSONB on PostgreSQL (production) and JSON on SQLite (tests).
    Preserves PostgreSQL JSONB behavior; allows API endpoint tests with in-memory SQLite.
    """
    impl = JSON
    cache_ok = True

    def load_dialect_impl(self, dialect):
        if dialect.name == "postgresql":
            return dialect.type_descriptor(JSONB())
        return dialect.type_descriptor(JSON())


class POI(Base):
    """
    SQLAlchemy model for the 'poi' table.
    Represents a Point of Interest with location, description, and metadata.
    """
    __tablename__ = "poi"

    id = Column(Text, primary_key=True)
    label = Column(Text)
    description = Column(Text)
    latitude = Column(Float, nullable=False)
    longitude = Column(Float, nullable=False)
    uri = Column(Text)
    type = Column(Text, nullable=True)
    city = Column(Text, nullable=True)
    department_code = Column(Text, nullable=True)
    theme = Column(Text, nullable=True)
    last_update = Column(DateTime)
    raw_json = Column(JSONBCompat)
    source_id = Column(Integer)
    created_at = Column(DateTime, server_default=func.now())

