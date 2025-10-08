import subprocess

command = [
    "python", "python-clients/scripts/tts/talk.py",
    "--server", "grpc.nvcf.nvidia.com:443", "--use-ssl",
    "--metadata", "function-id", "5e607c81-7aa6-44ce-a11d-9e08f0a3fe49",
    "--metadata", "authorization", "Bearer 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",
    "--list-voices"
]

subprocess.run(command)