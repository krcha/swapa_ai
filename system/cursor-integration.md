# Cursor Multi-Agent Development Integration Guide

## Cursor Setup for Multi-Agent Development

### 1. Configure AI Models in Cursor
- **Primary Model**: GPT-4 (for complex reasoning and architecture)
- **Secondary Model**: Gemini (for code generation and optimization)
- **Agent Assignment**: Different models for different agent types

### 2. Cursor Composer Integration
Use Cursor Composer to manage multiple files and coordinate agent work across the entire codebase.

## Agent Execution Commands for Cursor

### Orchestrator Agent Activation
```
@system/agent-hierarchy.json @system/state-manager.json @docs/laravel-marketplace-spec.md

AGENT: Orchestrator
CONTEXT: Multi-agent Laravel marketplace development system initialization
MODELS: GPT-4 (strategic decisions), Gemini (task coordination)

MISSION:
1. Analyze the Laravel marketplace requirements 
2. Initialize the multi-agent development system
3. Assign appropriate agents to development phases
4. Set up coordination protocols and communication channels
5. Create initial task distribution and timeline

CURRENT STATE: INITIALIZATION
NEXT STATE TARGET: PLANNING

Execute orchestration strategy and prepare system for development kickoff.
```

### Critical Thinker Agent Activation  
```
@agents/critical-thinker/critical-thinker-prompt.md @docs/laravel-marketplace-spec.md @system/state-manager.json

AGENT: Critical Thinker (Devil's Advocate)
CONTEXT: Laravel marketplace project analysis and risk assessment
MODEL: GPT-4 (critical analysis and reasoning)

MISSION:
1. Challenge the proposed Laravel marketplace concept
2. Identify potential weaknesses in the business model
3. Question technical architecture assumptions
4. Assess risks in JMBG verification and token system
5. Propose alternative approaches and solutions

FOCUS AREAS:
- Token economics viability
- JMBG verification barriers to entry
- Serbian payment integration risks
- Scalability and performance concerns
- Regulatory compliance challenges

Provide comprehensive risk assessment and constructive challenges.
```

### Senior Developer Agent Activation
```
@agents/senior-dev/ @system/agent-hierarchy.json @docs/laravel-marketplace-spec.md

AGENT: Senior Developer (System Architect)
CONTEXT: Laravel marketplace architecture design
MODEL: GPT-4 (architecture) + Gemini (implementation)

MISSION:
1. Design comprehensive system architecture
2. Define technology stack and framework decisions
3. Create database schema and relationships
4. Plan security architecture for JMBG and payments
5. Design scalable performance architecture

DELIVERABLES:
- System architecture documentation
- Database ERD and schema design
- API architecture and contracts
- Security implementation plan
- Performance and caching strategy

Coordinate with Critical Thinker for architecture validation.
```

### Dynamic Agent Switching Commands

#### Performance-Based Switching
```
@system/state-manager.json @system/monitoring/

ORCHESTRATOR COMMAND: Agent Performance Analysis
TRIGGER: Task completion time > threshold OR quality score < 0.8

ANALYSIS REQUIRED:
1. Current agent performance metrics
2. Task complexity vs. agent capabilities  
3. Alternative agent suitability
4. Switching cost vs. benefit analysis

IF switching recommended:
- Document switching reason
- Transfer context to new agent
- Update task assignments
- Monitor improvement

Execute agent performance evaluation and switching decision.
```

#### Specialized Expertise Switching
```
@system/agent-hierarchy.json @tasks/current-task.md

ORCHESTRATOR COMMAND: Expertise-Based Agent Assignment
TRIGGER: Task requires specialized knowledge outside current agent scope

ANALYSIS:
1. Task complexity and requirements analysis
2. Agent specialization matching
3. Current workload and availability
4. Integration dependencies

REASSIGNMENT PROTOCOL:
- Identify best-suited agent
- Transfer all relevant context
- Establish collaboration protocols
- Set performance expectations

Execute specialized agent assignment for current task.
```

## State Management in Cursor

### State Transition Commands
```
# Moving from PLANNING to DEVELOPMENT
@system/state-manager.json @agents/orchestrator/

ORCHESTRATOR: State Transition Request
FROM: PLANNING  
TO: DEVELOPMENT
VALIDATION: Critical Thinker approval required

PRE-TRANSITION CHECKLIST:
- [ ] Architecture reviewed and approved
- [ ] Critical concerns addressed
- [ ] Development tasks defined
- [ ] Agent assignments confirmed
- [ ] Rollback point created

Execute state transition with validation.
```

### Rollback Execution
```
@rollback/snapshots/ @system/state-manager.json @debug/

EMERGENCY ROLLBACK PROTOCOL
TRIGGER: [Critical issue description]
ROLLBACK TO: [Snapshot identifier]
SCOPE: [System-wide | Agent-specific | Feature-specific]

ROLLBACK STEPS:
1. Create emergency snapshot of current state
2. Identify rollback target and scope
3. Execute rollback procedure
4. Validate system integrity
5. Document rollback reason and lessons learned

Execute immediate rollback and recovery procedures.
```

## GitHub Integration Commands

### Automated Git Operations
```bash
# Create agent-specific branches
git checkout -b orchestrator-decisions
git checkout -b critical-analysis  
git checkout -b senior-dev-architecture
git checkout -b development-features

# Agent work commit pattern
git add .
git commit -m "[AGENT-NAME] [TASK-TYPE]: [Description]"
git push origin [branch-name]
```

### Branch Management for Agents
```
@.git/ @system/agent-hierarchy.json

ORCHESTRATOR: Git Branch Management
COMMAND: Create agent-specific development branches

BRANCH STRATEGY:
- main: Integration and releases
- orchestrator-*: System coordination decisions
- critical-analysis-*: Risk assessments and challenges  
- architecture-*: System design and technical decisions
- feature-*: Development agent work
- hotfix-*: Emergency fixes and rollbacks

Create branch structure and establish merge protocols.
```

## Debug Interface Commands

### Interactive Debug Session
```
@debug/ @system/state-manager.json @system/logs/

DEBUG CONSOLE ACTIVATION
SESSION: Laravel Marketplace Development
CONTEXT: [Current issue or investigation]

AVAILABLE COMMANDS:
- system status: Overall system health
- agent status [agent-name]: Specific agent performance  
- state history: State transition timeline
- rollback list: Available rollback points
- issue analysis [issue-id]: Detailed problem analysis

Enter debug mode for system investigation.
```

### Performance Monitoring
```
@system/monitoring/ @agents/

MONITORING DASHBOARD
METRICS TRACKING:
- Agent task completion times
- Code quality scores  
- Integration success rates
- System performance benchmarks
- Resource utilization

ALERTS:
- Performance degradation
- Quality score drops
- Integration failures
- Resource bottlenecks

Generate performance report and identify optimization opportunities.
```

## Multi-Model Coordination

### GPT-4 for Strategic Decisions
```
MODEL: GPT-4
USE CASES:
- Orchestrator strategic planning
- Critical Thinker risk analysis
- Senior Developer architecture decisions
- Complex problem solving

ADVANTAGES:
- Superior reasoning and analysis
- Better at connecting complex concepts
- Excellent for strategic planning
```

### Gemini for Implementation
```  
MODEL: Gemini
USE CASES:
- Code generation and optimization
- Database schema creation
- API implementation
- Testing and debugging

ADVANTAGES:
- Fast code generation
- Good at following patterns
- Excellent for repetitive tasks
```

## Workflow Integration Example

### Complete Development Cycle in Cursor
```
# 1. Initialize System
[Orchestrator + Critical Thinker activation]

# 2. Planning Phase
[Senior Dev architecture + Critical Thinker challenges]

# 3. Development Phase  
[Specialized agents with Supervisor oversight]

# 4. Integration Phase
[Debug Agent + Orchestrator coordination]

# 5. Deployment Phase
[DevOps Agent + system validation]
```

This integration allows you to leverage Cursor's full capabilities while maintaining the sophisticated multi-agent coordination system with automatic switching and critical analysis.