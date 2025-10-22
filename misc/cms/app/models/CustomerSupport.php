<?php

class RealtimeAgent {
    public string $name;
    public string $instructions;
    public array $tools;

    public function __construct(string $name, string $instructions, array $tools = []) {
        $this->name = $name;
        $this->instructions = $instructions;
        $this->tools = $tools;
    }

    // Lägg till ett verktyg/agent
    public function addTool(RealtimeAgent $agent): void {
        $this->tools[] = $agent;
    }

    // Exempelmetod för att lista verktyg
    public function listTools(): array {
        $toolNames = [];
        foreach ($this->tools as $tool) {
            $toolNames[] = $tool->name;
        }
        return $toolNames;
    }
}

// --------------------------
// Skapa agenter
// --------------------------

$productSpecialist = new RealtimeAgent(
    name: "Product Specialist",
    instructions: "You are a product specialist. You are responsible for answering questions about our products."
);

$triageAgent = new RealtimeAgent(
    name: "Triage Agent",
    instructions: "You are a customer service frontline agent. You are responsible for triaging calls to the appropriate agent.",
    tools: [$productSpecialist]
);

// --------------------------
// Testa
// --------------------------
echo "Triage Agent tools: " . implode(", ", $triageAgent->listTools()) . "\n";
