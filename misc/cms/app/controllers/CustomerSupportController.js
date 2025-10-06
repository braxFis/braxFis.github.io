//Sample approach...?

//Option 1

import { RealtimeAgent } from "@openai/agents/realtime";

const productSpecialist = new RealtimeAgent({
  name: 'Product Specialist',
  instructions: 'You are a product specialist. You are responsible for answering questions about our products.',
});

const triageAgent = new RealtimeAgent({
  name: 'Triage Agent',
  instructions: 'You are a customer service frontline agent. You are responsible for triaging calls to the appropriate agent.',
  tools: [
    productSpecialist,
  ]
})

// Option 2

import { Agent } from '@openai/agents';

const agent = new Agent({
  name: 'Haiku Agent',
  instructions: 'Always respond in haiku form.',
  model: 'gpt-5-nano', // optional – falls back to the default model
});

//Refine to meet your specific needs...

//You need:
/*
* Customers want to know how to subscribe
* Customers want to know how to pay
* Customers want to know what content is there
* Customers want to know what premium offers
* Customers want to know what standard offers
* Customers want to know duration of the subscription
* Customers want to know how to end the subscription early
* Base it off of these simple details and let ai handle the heavy load!
* */
