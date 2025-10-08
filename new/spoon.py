import subprocess

command = [
    "python", "python-clients/scripts/tts/talk.py",
    "--server", "grpc.nvcf.nvidia.com:443", "--use-ssl",
    "--metadata", "function-id", "5e607c81-7aa6-44ce-a11d-9e08f0a3fe49",
    "--metadata", "authorization", "Bearer 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",
    "--text", "Well, you have a point there. But those wounds are still not on the critical level. Even it if may hurt a little. Just sit tight. We will get there in a moment. I have driven this way many times before. Trust me",
    "--voice", "English-US-RadTTS.Male-Neutral",
    "--output", "chapter1_ride_to_the_hospital_ambulance_05.wav"
]

subprocess.run(command)