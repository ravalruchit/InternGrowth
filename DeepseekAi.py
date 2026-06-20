from openai import OpenAI

client = OpenAI(
    base_url="https://api.featherless.ai/v1",
    api_key="rc_782aa725b8cf8092c5dc1c8e8cb86c16c086145674d3269c50f32f28ce11d223",
)

response = client.chat.completions.create(
    model="deepseek-ai/DeepSeek-V4-Pro",
    max_tokens=4096,
    messages=[
        {"role": "system", "content": "You are a helpful assistant."},
        {"role": "user", "content": "What is the fastest way to get to the airport?"},
    ],
)

print(response.choices[0].message.content)