# SDR Application - Technical Architecture

## Overview

This document outlines the high-level technical architecture for an AI-powered Sales Development Representative (SDR) application. The system is designed around **3 autonomous agents** that work together to automate and enhance the sales development process.

---

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                                    SDR APPLICATION                                       │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                          │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                           PRESENTATION LAYER                                     │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │   Web App    │  │  Dashboard   │  │   Admin UI   │  │  Mobile App  │         │    │
│  │  │   (React)    │  │  (Analytics) │  │  (Settings)  │  │  (Optional)  │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                           │                                              │
│                                           ▼                                              │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              API GATEWAY                                         │    │
│  │           (Authentication, Rate Limiting, Request Routing)                       │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                           │                                              │
│           ┌───────────────────────────────┼───────────────────────────────┐              │
│           ▼                               ▼                               ▼              │
│  ┌─────────────────┐           ┌─────────────────┐           ┌─────────────────┐        │
│  │                 │           │                 │           │                 │        │
│  │    AGENT 1      │◄─────────►│    AGENT 2      │◄─────────►│    AGENT 3      │        │
│  │  (SDR Agent)    │           │   (TBD Agent)   │           │   (TBD Agent)   │        │
│  │                 │           │                 │           │                 │        │
│  └────────┬────────┘           └────────┬────────┘           └────────┬────────┘        │
│           │                             │                             │                  │
│           └─────────────────────────────┼─────────────────────────────┘                  │
│                                         ▼                                                │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         AGENT ORCHESTRATION LAYER                                │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │    Agent     │  │    Task      │  │   Workflow   │  │    State     │         │    │
│  │  │   Registry   │  │    Queue     │  │    Engine    │  │   Manager    │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                           │                                              │
│                                           ▼                                              │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              AI/ML LAYER                                         │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │     LLM      │  │   Vector     │  │  Embedding   │  │   Prompt     │         │    │
│  │  │   Gateway    │  │    Store     │  │    Model     │  │   Manager    │         │    │
│  │  │(GPT/Claude)  │  │  (Pinecone)  │  │              │  │              │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                           │                                              │
│                                           ▼                                              │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                            DATA LAYER                                            │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │  PostgreSQL  │  │    Redis     │  │     S3       │  │  Event Store │         │    │
│  │  │  (Primary)   │  │   (Cache)    │  │  (Documents) │  │   (Logs)     │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                          │
└─────────────────────────────────────────────────────────────────────────────────────────┘
                                           │
                                           ▼
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                              EXTERNAL INTEGRATIONS                                       │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐         │
│  │ LinkedIn │ │  Email   │ │  Twilio  │ │   CRM    │ │ Calendar │ │  Social  │         │
│  │   API    │ │ Provider │ │  (Calls) │ │(Salesforce)│ │  (Cal)   │ │ Media API│         │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘         │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Agent 1: SDR Agent ("Agent Jules")

### Purpose
The primary sales development agent responsible for prospecting, outreach, and meeting booking through multiple channels.

### Architecture Detail

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                              AGENT 1: SDR AGENT (JULES)                                  │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                          │
│   KNOWLEDGE INPUTS                              CORE PROCESSING                          │
│   ┌─────────────────────┐                      ┌─────────────────────────────────────┐  │
│   │  Company Knowledge  │                      │         AGENT BRAIN                 │  │
│   │  ┌───────────────┐  │                      │  ┌─────────────────────────────┐   │  │
│   │  │Company Profile│  │                      │  │      LLM REASONING          │   │  │
│   │  ├───────────────┤  │                      │  │   (GPT-4 / Claude 3.5)      │   │  │
│   │  │ Case Studies  │  │──────────────────────►  │                             │   │  │
│   │  ├───────────────┤  │                      │  │  • Context Understanding    │   │  │
│   │  │  Value Prop   │  │                      │  │  • Decision Making          │   │  │
│   │  ├───────────────┤  │                      │  │  • Content Generation       │   │  │
│   │  │   Products    │  │                      │  │  • Personalization          │   │  │
│   │  ├───────────────┤  │                      │  └─────────────────────────────┘   │  │
│   │  │   Services    │  │                      │                 │                   │  │
│   │  ├───────────────┤  │                      │                 ▼                   │  │
│   │  │  Challenges   │  │                      │  ┌─────────────────────────────┐   │  │
│   │  └───────────────┘  │                      │  │     TOOL ORCHESTRATOR       │   │  │
│   └─────────────────────┘                      │  │                             │   │  │
│                                                │  │  • Research Tools           │   │  │
│   ┌─────────────────────┐                      │  │  • Outreach Tools           │   │  │
│   │  Marketing Inputs   │                      │  │  • CRM Tools                │   │  │
│   │  ┌───────────────┐  │                      │  │  • Calendar Tools           │   │  │
│   │  │      ICP      │  │──────────────────────►  │  • Content Tools            │   │  │
│   │  ├───────────────┤  │                      │  └─────────────────────────────┘   │  │
│   │  │  Lead Lists   │  │                      └─────────────────────────────────────┘  │
│   │  ├───────────────┤  │                                        │                      │
│   │  │LinkedIn Sales │  │                                        │                      │
│   │  │  Navigator    │  │                                        ▼                      │
│   │  └───────────────┘  │                      ┌─────────────────────────────────────┐  │
│   └─────────────────────┘                      │      MESSAGING SEQUENCE ENGINE      │  │
│                                                │  ┌─────────┐ ┌─────────┐ ┌────────┐ │  │
│   ┌─────────────────────┐                      │  │ Step 1  │►│ Step 2  │►│Step N  │ │  │
│   │  Research Inputs    │                      │  │(LinkedIn│ │ (Email) │ │(Follow │ │  │
│   │  ┌───────────────┐  │                      │  │ Connect)│ │         │ │  up)   │ │  │
│   │  │Meeting Notes  │  │                      │  └─────────┘ └─────────┘ └────────┘ │  │
│   │  ├───────────────┤  │──────────────────────►  │                                 │  │
│   │  │    Links      │  │                      │  │  • A/B Testing Logic            │  │
│   │  ├───────────────┤  │                      │  │  • Timing Optimization          │  │
│   │  │  Documents    │  │                      │  │  • Response Detection           │  │
│   │  ├───────────────┤  │                      │  │  • Escalation Rules             │  │
│   │  │News Research  │  │                      └─────────────────────────────────────┘  │
│   │  └───────────────┘  │                                        │                      │
│   └─────────────────────┘                                        │                      │
│                                                                  ▼                      │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                   OUTPUT CHANNELS                                        │
│                                                                                          │
│   ┌───────────────┐    ┌───────────────┐    ┌───────────────┐    ┌───────────────┐      │
│   │   LINKEDIN    │    │    EMAIL      │    │     CALL      │    │    SOCIAL     │      │
│   │   OUTREACH    │    │   OUTREACH    │    │   OUTREACH    │    │    CONTENT    │      │
│   │               │    │               │    │               │    │               │      │
│   │ • Connection  │    │ • Cold Email  │    │ • Call Script │    │ • Instagram   │      │
│   │ • InMail      │    │ • Follow-up   │    │ • Voicemail   │    │ • Facebook    │      │
│   │ • Comments    │    │ • Sequences   │    │ • Scheduling  │    │ • LinkedIn    │      │
│   │ • Messages    │    │               │    │               │    │ • Blog        │      │
│   └───────┬───────┘    └───────┬───────┘    └───────┬───────┘    └───────┬───────┘      │
│           │                    │                    │                    │              │
│           └────────────────────┴────────────────────┴────────────────────┘              │
│                                           │                                              │
│                                           ▼                                              │
│                              ┌───────────────────────┐                                   │
│                              │    PROSPECT → MEETING │                                   │
│                              │        BOOKED         │                                   │
│                              └───────────────────────┘                                   │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

### Agent 1 Component Breakdown

#### 1. Knowledge Base Module
```
┌─────────────────────────────────────────────────────────┐
│                   KNOWLEDGE BASE                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌─────────────────┐    ┌─────────────────────────────┐ │
│  │  Document       │    │      Vector Store           │ │
│  │  Ingestion      │───►│      (Pinecone/Weaviate)    │ │
│  │  Pipeline       │    │                             │ │
│  └─────────────────┘    │  • Company docs embeddings  │ │
│         │               │  • Case study embeddings    │ │
│         ▼               │  • Product info embeddings  │ │
│  ┌─────────────────┐    │                             │ │
│  │  Chunking &     │    └─────────────────────────────┘ │
│  │  Embedding      │                                    │
│  │  (OpenAI Ada)   │    ┌─────────────────────────────┐ │
│  └─────────────────┘    │      RAG Pipeline           │ │
│                         │                             │ │
│                         │  Query → Retrieve → Augment │ │
│                         │         → Generate          │ │
│                         └─────────────────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

#### 2. Lead Management Module
```
┌─────────────────────────────────────────────────────────┐
│                  LEAD MANAGEMENT                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   │
│  │     ICP      │  │    Lead      │  │    Lead      │   │
│  │   Matcher    │  │  Enrichment  │  │   Scoring    │   │
│  │              │  │              │  │              │   │
│  │ • Firmograph │  │ • Apollo.io  │  │ • Fit Score  │   │
│  │ • Technograph│  │ • Clearbit   │  │ • Intent     │   │
│  │ • Intent     │  │ • ZoomInfo   │  │ • Engagement │   │
│  └──────────────┘  └──────────────┘  └──────────────┘   │
│          │                │                 │            │
│          └────────────────┼─────────────────┘            │
│                           ▼                              │
│                  ┌──────────────┐                        │
│                  │  Prioritized │                        │
│                  │  Lead Queue  │                        │
│                  └──────────────┘                        │
└─────────────────────────────────────────────────────────┘
```

#### 3. Outreach Engine Module
```
┌─────────────────────────────────────────────────────────┐
│                   OUTREACH ENGINE                        │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌─────────────────────────────────────────────────┐    │
│  │            SEQUENCE BUILDER                      │    │
│  │                                                  │    │
│  │   Day 1        Day 3        Day 5       Day 7   │    │
│  │  ┌─────┐     ┌─────┐     ┌─────┐     ┌─────┐   │    │
│  │  │ LI  │────►│Email│────►│ LI  │────►│Call │   │    │
│  │  │Conn │     │  #1 │     │ Msg │     │     │   │    │
│  │  └─────┘     └─────┘     └─────┘     └─────┘   │    │
│  │                                                  │    │
│  └─────────────────────────────────────────────────┘    │
│                                                          │
│  ┌─────────────────────────────────────────────────┐    │
│  │           PERSONALIZATION ENGINE                 │    │
│  │                                                  │    │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐      │    │
│  │  │ Prospect │  │ Company  │  │  Timing  │      │    │
│  │  │ Research │  │ Triggers │  │  Logic   │      │    │
│  │  └──────────┘  └──────────┘  └──────────┘      │    │
│  └─────────────────────────────────────────────────┘    │
│                                                          │
│  ┌─────────────────────────────────────────────────┐    │
│  │           CHANNEL ADAPTERS                       │    │
│  │                                                  │    │
│  │  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐   │    │
│  │  │LinkedIn│ │ Email  │ │ Phone  │ │ Social │   │    │
│  │  │Adapter │ │Adapter │ │Adapter │ │Adapter │   │    │
│  │  └────────┘ └────────┘ └────────┘ └────────┘   │    │
│  └─────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────┘
```

#### 4. Content Generation Module
```
┌─────────────────────────────────────────────────────────┐
│                 CONTENT GENERATION                       │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌─────────────────────────────────────────────────┐    │
│  │            TOPIC GENERATOR                       │    │
│  │                                                  │    │
│  │  News/Trends ──► AI Analysis ──► Topic Ideas    │    │
│  └─────────────────────────────────────────────────┘    │
│                          │                               │
│                          ▼                               │
│  ┌─────────────────────────────────────────────────┐    │
│  │            CONTENT WRITER                        │    │
│  │                                                  │    │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐      │    │
│  │  │  Blog    │  │  Social  │  │  Email   │      │    │
│  │  │  Posts   │  │  Posts   │  │Templates │      │    │
│  │  └──────────┘  └──────────┘  └──────────┘      │    │
│  └─────────────────────────────────────────────────┘    │
│                          │                               │
│                          ▼                               │
│  ┌─────────────────────────────────────────────────┐    │
│  │            SCHEDULER                             │    │
│  │                                                  │    │
│  │  Content Queue ──► Optimal Time ──► Publish     │    │
│  └─────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────┘
```

---

## Agent 2: [PLACEHOLDER - Awaiting Details]

*Architecture details will be added once agent specifications are provided.*

---

## Agent 3: [PLACEHOLDER - Awaiting Details]

*Architecture details will be added once agent specifications are provided.*

---

## Technology Stack Recommendations

### Core Infrastructure
| Component | Technology | Purpose |
|-----------|------------|---------|
| Cloud Provider | AWS / GCP | Hosting & Infrastructure |
| Container Orchestration | Kubernetes (EKS/GKE) | Agent scaling & deployment |
| API Gateway | Kong / AWS API Gateway | Request routing & auth |
| Message Queue | Redis / RabbitMQ | Task queue & pub/sub |

### AI/ML Stack
| Component | Technology | Purpose |
|-----------|------------|---------|
| LLM Provider | OpenAI GPT-4 / Anthropic Claude | Agent reasoning |
| Vector Database | Pinecone / Weaviate | Knowledge retrieval |
| Embedding Model | OpenAI Ada-002 | Document embeddings |
| Agent Framework | LangChain / LangGraph | Agent orchestration |

### Data Storage
| Component | Technology | Purpose |
|-----------|------------|---------|
| Primary Database | PostgreSQL | Structured data |
| Cache | Redis | Session & rate limiting |
| Document Store | S3 | File storage |
| Search | Elasticsearch | Full-text search |

### External Integrations
| Service | Provider Options | Purpose |
|---------|-----------------|---------|
| Email | SendGrid / AWS SES / Instantly | Email outreach |
| LinkedIn | LinkedIn API / Phantombuster | Social outreach |
| Phone/SMS | Twilio / Aircall | Call automation |
| CRM | Salesforce / HubSpot | Lead management |
| Calendar | Cal.com / Calendly | Meeting scheduling |
| Enrichment | Apollo / Clearbit / ZoomInfo | Lead data |

---

## Data Flow Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                              DATA FLOW OVERVIEW                                          │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                          │
│   ┌──────────┐      ┌──────────┐      ┌──────────┐      ┌──────────┐      ┌──────────┐ │
│   │  Input   │      │  Enrich  │      │  Process │      │  Execute │      │  Track   │ │
│   │  Sources │─────►│  & Store │─────►│  & Plan  │─────►│  Actions │─────►│  Results │ │
│   └──────────┘      └──────────┘      └──────────┘      └──────────┘      └──────────┘ │
│                                                                                          │
│   • Lead Lists       • Enrichment     • Agent           • Send Email    • Open Rates    │
│   • ICP Data           APIs             Reasoning       • LinkedIn Msg  • Reply Rates   │
│   • Documents        • Vector Store   • Sequence        • Schedule      • Meeting       │
│   • Company Info     • PostgreSQL       Selection         Call            Booked        │
│                                       • Personalize                                      │
│                                                                                          │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Security Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                              SECURITY LAYERS                                             │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                          │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │  AUTHENTICATION & AUTHORIZATION                                                  │    │
│  │  • OAuth 2.0 / JWT tokens                                                       │    │
│  │  • Role-based access control (RBAC)                                             │    │
│  │  • API key management for integrations                                          │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                          │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │  DATA PROTECTION                                                                 │    │
│  │  • Encryption at rest (AES-256)                                                 │    │
│  │  • Encryption in transit (TLS 1.3)                                              │    │
│  │  • PII handling & data masking                                                  │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                          │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │  COMPLIANCE                                                                      │    │
│  │  • GDPR compliance for EU prospects                                             │    │
│  │  • CAN-SPAM compliance for emails                                               │    │
│  │  • LinkedIn ToS compliance                                                      │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                          │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Deployment Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                              KUBERNETES CLUSTER                                          │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                          │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │  NAMESPACE: sdr-app                                                              │    │
│  │                                                                                  │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐            │    │
│  │  │  api-svc    │  │ agent1-svc  │  │ agent2-svc  │  │ agent3-svc  │            │    │
│  │  │  (3 pods)   │  │  (3 pods)   │  │  (3 pods)   │  │  (3 pods)   │            │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘            │    │
│  │                                                                                  │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                             │    │
│  │  │ worker-svc  │  │ scheduler   │  │  webhook    │                             │    │
│  │  │ (auto-scale)│  │  (1 pod)    │  │  (2 pods)   │                             │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘                             │    │
│  │                                                                                  │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                          │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │  MANAGED SERVICES                                                                │    │
│  │                                                                                  │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐            │    │
│  │  │    RDS      │  │ ElastiCache │  │     S3      │  │ CloudWatch  │            │    │
│  │  │ (Postgres)  │  │  (Redis)    │  │  (Storage)  │  │   (Logs)    │            │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘            │    │
│  │                                                                                  │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                          │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Next Steps

1. **Agent 2 Definition** - Awaiting specifications
2. **Agent 3 Definition** - Awaiting specifications
3. **Inter-agent Communication** - Define how agents collaborate
4. **Detailed API Specifications** - OpenAPI/Swagger docs
5. **Database Schema Design** - Entity relationships
6. **CI/CD Pipeline Setup** - GitHub Actions / GitLab CI

---

*Document Version: 1.0*
*Last Updated: January 2026*
*Status: In Progress - Awaiting Agent 2 & 3 specifications*
