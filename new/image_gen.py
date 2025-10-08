import requests
import os

# Replace this with your actual OpenAI API key or use an environment variable
api_key = "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"

headers = {
    "Content-Type": "application/json",
    "Authorization": f"Bearer {api_key}"
}

data = {
    "model": "dall-e-3",
    "prompt": "a young guy in his 40s is the joker (wearing a purple suit without anything on his head) and holding a loaded shotgun in one hand, he tries to slide down a pyramid of money",
    "n": 1,
    "quality": "hd",
    "size": "1792x1024"
}

# Generate image
response = requests.post(
    "https://api.openai.com/v1/images/generations",
    headers=headers,
    json=data
)

response_json = response.json()

# Extract image URL
image_url = response_json['data'][0]['url']
print("Image URL:", image_url)

# Download the image
image_response = requests.get(image_url)

# Save the image to a file
with open("joker.jpg", "wb") as f:
    f.write(image_response.content)
