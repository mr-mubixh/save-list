# AI Agent Platform - Complete Context Prompt

> Copy everything below this line and paste into any AI (Claude, ChatGPT, etc.) to continue the conversation.

---

## 🚀 PROJECT CONTEXT PROMPT

```
I am building a Multi-Tenant AI Agent Platform (B2B SaaS) that automates revenue operations. Here's the complete context:

===================================================================================
                              PROJECT OVERVIEW
===================================================================================

PRODUCT TYPE: B2B SaaS Platform
BUSINESS MODEL: Sell modular AI agents to multiple clients (companies)
ARCHITECTURE: Multi-tenant (each client has isolated data)
EXTENSIBILITY: Can add more agents in the future

===================================================================================
                           THE 3 INDEPENDENT AGENTS
===================================================================================

We have 3 AI agents. Each agent is:
- A STANDALONE product (can be sold independently)
- Serves DIFFERENT business functions
- Used by DIFFERENT teams within a company
- Does NOT depend on other agents to function
- CAN integrate with others but DOESN'T HAVE TO

AGENT 1: JULES (Marketing Agent)
├── Function: Marketing & Lead Generation
├── Target User: Marketing Team
├── Capabilities:
│   ├── Lead Generation
│   ├── Prospecting
│   ├── Email Outreach
│   ├── LinkedIn Outreach
│   ├── Content Generation
│   └── Social Media Publishing
└── Goal: Generate leads → Book meetings

AGENT 2: JOY (Sales Agent)
├── Function: Sales & Deal Closing
├── Target User: Sales Team
├── Capabilities:
│   ├── Meeting Scheduling
│   ├── Proposal Generation
│   ├── Live Product Expert Bot
│   ├── CRM Auto-Update
│   ├── Sales Acceleration
│   └── Deal Tracking
└── Goal: Meetings → Close deals

AGENT 3: GEORGE (Customer Success Agent)
├── Function: Customer Success & Support
├── Target User: Customer Success / Support Team
├── Capabilities:
│   ├── Customer Onboarding
│   ├── Training & Adoption
│   ├── 24/7 Support (Phone, Email, Chat)
│   ├── NPS/CSAT Tracking
│   ├── Health Scoring
│   └── Multi-lingual Support
└── Goal: Onboard → Retain customers

CLIENT PURCHASE SCENARIOS:
- Client A: Buys only Jules (just needs lead gen)
- Client B: Buys only Joy (has leads, needs help closing)
- Client C: Buys only George (needs customer support)
- Client D: Buys all 3 (full RevOps automation)
- Client E: Buys Jules + George (marketing + support)

===================================================================================
                          TECHNICAL ARCHITECTURE
===================================================================================

SIMPLE FLOW:

Dashboard ──────► FastAPI ──────► LangChain Agent ──────► n8n Workflows
(React)          (Backend)       (AI + RAG)              (Integrations)
    │                │                  │                       │
    ▼                ▼                  ▼                       ▼
Config UI       PostgreSQL      Pinecone + OpenAI      Email, LinkedIn,
Analytics       Auth/Tenants    Reasoning Engine       CRM, Calendar...


DETAILED ARCHITECTURE:

┌──────────────────────────────────────────────────────────────────────────┐
│                                                                          │
│   LAYER 1: FRONTEND                                                      │
│   ┌────────────────────────────────────────────────────────────────┐    │
│   │  Next.js / React Dashboard                                      │    │
│   │  • Agent Configuration Studio                                   │    │
│   │  • Knowledge Base Upload                                        │    │
│   │  • Integration Setup (CRM, Email, etc.)                        │    │
│   │  • Analytics & Reporting                                        │    │
│   └────────────────────────────────────────────────────────────────┘    │
│                                    │                                     │
│                                    ▼                                     │
│   LAYER 2: BACKEND API                                                   │
│   ┌────────────────────────────────────────────────────────────────┐    │
│   │  Python FastAPI                                                 │    │
│   │  • Authentication (JWT)                                         │    │
│   │  • Tenant Management (Multi-tenant)                            │    │
│   │  • Agent Configuration Storage                                  │    │
│   │  • API Gateway                                                  │    │
│   │                                                                 │    │
│   │  Connected to: PostgreSQL (primary DB), Redis (cache/queue)    │    │
│   └────────────────────────────────────────────────────────────────┘    │
│                                    │                                     │
│                                    ▼                                     │
│   LAYER 3: AI AGENT                                                      │
│   ┌────────────────────────────────────────────────────────────────┐    │
│   │  LangChain / LangGraph                                          │    │
│   │  • Agent Reasoning & Decision Making                           │    │
│   │  • RAG Pipeline (Retrieval Augmented Generation)               │    │
│   │  • Content Generation & Personalization                        │    │
│   │                                                                 │    │
│   │  Connected to:                                                  │    │
│   │  • Pinecone (Vector DB for knowledge base, per-tenant)         │    │
│   │  • OpenAI GPT-4 / Claude (LLM for generation)                  │    │
│   └────────────────────────────────────────────────────────────────┘    │
│                                    │                                     │
│                                    ▼                                     │
│   LAYER 4: WORKFLOW ENGINE                                               │
│   ┌────────────────────────────────────────────────────────────────┐    │
│   │  n8n (Self-Hosted)                                              │    │
│   │                                                                 │    │
│   │  JULES Workflows:                                               │    │
│   │  • Lead Prospecting, Email Sequences, LinkedIn Outreach        │    │
│   │                                                                 │    │
│   │  JOY Workflows:                                                 │    │
│   │  • Meeting Scheduler, Proposal Generator, CRM Sync             │    │
│   │                                                                 │    │
│   │  GEORGE Workflows:                                              │    │
│   │  • Onboarding Sequences, Support Tickets, NPS Surveys          │    │
│   │                                                                 │    │
│   │  Integrations: Email (SendGrid), LinkedIn, CRM (Salesforce/    │    │
│   │  HubSpot), Calendar (Cal.com), Phone (Twilio), Enrichment      │    │
│   │  (Apollo), Support (Zendesk/Intercom)                          │    │
│   └────────────────────────────────────────────────────────────────┘    │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘

===================================================================================
                            TECHNOLOGY STACK
===================================================================================

| Layer           | Technology              | Purpose                           |
|-----------------|-------------------------|-----------------------------------|
| Frontend        | Next.js / React         | Dashboard & Configuration UI      |
| Backend API     | Python FastAPI          | Auth, Tenants, Config Management  |
| Database        | PostgreSQL              | Structured Data (Row-Level Security)|
| Cache/Queue     | Redis + BullMQ          | Sessions, Rate Limits, Job Queue  |
| AI Framework    | LangChain / LangGraph   | Agent Reasoning & Tools           |
| LLM Provider    | OpenAI GPT-4 / Claude   | Text Generation                   |
| Vector Database | Pinecone                | Knowledge Base RAG (Per-Tenant)   |
| Workflow Engine | n8n (Self-Hosted)       | Automation & Integrations         |
| File Storage    | AWS S3                  | Documents & Generated Content     |
| Infrastructure  | Docker + Kubernetes     | Container Orchestration           |

===================================================================================
                          BUILD ORDER & PHASES
===================================================================================

PHASE 1: FastAPI Backend (Week 1-3) ← START HERE
├── Project setup with Docker
├── PostgreSQL database schema (tenants, users, agents, configs)
├── Authentication APIs (register, login, JWT)
├── Tenant management APIs
├── Agent configuration APIs
└── Knowledge base upload endpoint

PHASE 2: LangChain AI Agent (Week 3-5)
├── Pinecone setup (per-tenant namespaces)
├── Document ingestion & embedding pipeline
├── RAG retrieval chain
├── Base agent class with tools
├── Jules, Joy, George specific logic
└── Agent execution API endpoints

PHASE 3: n8n Workflows (Week 5-7)
├── Self-hosted n8n installation
├── Integration credentials setup
├── Jules workflows (email, LinkedIn, content)
├── Joy workflows (meetings, proposals, CRM)
├── George workflows (onboarding, support, NPS)
└── Connect workflows to AI agent API

PHASE 4: Frontend Dashboard (Week 6-8)
├── Next.js project setup
├── Authentication (NextAuth)
├── Agent configuration UI
├── Knowledge base upload UI
├── Analytics dashboard
└── Integration setup UI

===================================================================================
                           COMPONENT RESPONSIBILITIES
===================================================================================

| Question                        | Answer                                    |
|---------------------------------|-------------------------------------------|
| Where to configure agents?      | Next.js Dashboard → Agent Config Studio   |
| Where is tenant/auth handled?   | FastAPI Backend → PostgreSQL              |
| Where is AI reasoning done?     | LangChain Agent Service                   |
| Where is knowledge base stored? | Pinecone (per-tenant namespace)           |
| Where are workflows executed?   | n8n (each agent has dedicated workflows)  |
| Where do integrations connect?  | n8n nodes → External APIs                 |

===================================================================================
                              CURRENT STATUS
===================================================================================

✅ Architecture designed
✅ All 3 agents defined (Jules, Joy, George)
✅ Technology stack finalized
✅ Build order established
⏳ Ready to start Phase 1: FastAPI Backend

===================================================================================
```

---

## HOW TO USE THIS PROMPT

1. Copy everything between the ``` marks above
2. Paste into Claude, ChatGPT, or any AI
3. Add your follow-up question, for example:

**Example follow-up questions you can ask:**

- "Create the FastAPI project structure for this application"
- "Design the PostgreSQL database schema for multi-tenancy"
- "Write the LangChain agent code for Jules"
- "Create n8n workflow JSON for email outreach"
- "Build the Next.js dashboard pages"
- "How should I handle per-tenant knowledge base in Pinecone?"
- "Write Docker Compose file for local development"

---

## QUICK CONTEXT (Shorter Version)

If you need a shorter version, use this:

```
I'm building a Multi-Tenant AI Agent Platform (B2B SaaS) with 3 independent agents:

1. JULES (Marketing): Lead gen, prospecting, email/LinkedIn outreach, content
2. JOY (Sales): Meeting scheduling, proposals, CRM updates, sales acceleration  
3. GEORGE (Customer Success): Onboarding, support, NPS/CSAT, health scoring

Tech Stack:
- Frontend: Next.js/React (dashboard)
- Backend: FastAPI + PostgreSQL (auth, tenants, configs)
- AI: LangChain + Pinecone + OpenAI (agent reasoning, RAG)
- Workflows: n8n self-hosted (automations, integrations)

Architecture Flow:
Dashboard → FastAPI → LangChain Agent → n8n Workflows → Integrations

Each agent is INDEPENDENT and can be sold separately to clients.
Clients can subscribe to 1, 2, or all 3 agents based on their needs.

[Your question here]
```

---

## FILES IN THIS PROJECT

| File | Description |
|------|-------------|
| `SDR_ARCHITECTURE.md` | Detailed technical architecture document |
| `TECHNICAL_DIAGRAM.md` | One-page technical diagram |
| `ARCHITECTURE_FLOW.txt` | Simple text-based flow diagram |
| `AI_Agent_Platform_Architecture.html` | Presentation-ready HTML (convert to PDF) |
| `PROMPT_FOR_AI_CONTINUATION.md` | This file - copy/paste prompt |

---
