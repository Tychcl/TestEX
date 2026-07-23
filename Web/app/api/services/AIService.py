from app.config import settings
from typing import Optional
import requests

class AIService():
    def __init__(self, model: str = "mistral-small-latest", temperature: float = 0.7, max_tokens: int = 150):
        self.model = model
        self.temperature = temperature
        self.max_tokens = max_tokens
        self.headers = {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "Authorization": f"Bearer {settings.MI_TOKEN}"}
        self.system_prompt = {'role':'system',
                              'content':"""You are a professional and friendly assistant replying to contact form messages.
                              Your response must be concise, helpful, and personalized — thank the user, address their question, and offer further assistance if relevant.
                              Reply in the same language as the user.
                              Keep tone warm but business-like.
                              Your entire reply MUST be under 255 characters(including spaces and punctuation).
                              Ideally 1–2 short sentences."""}
        self.url = "https://api.mistral.ai/v1/chat/completions"
        
    def get_ai_answer(self, message: str) -> Optional[str]:
        messages: list = [self.system_prompt, {'role': 'user', 'content': message}]
        
        payload = {
            "model": self.model,
            "messages": messages,
            "temperature": self.temperature,
            "max_tokens": self.max_tokens,
            "top_p": 1,
            "stream": False,
            "safe_prompt": False,
        }
        
        try:
            response = requests.post(self.url, headers=self.headers, json=payload, timeout=30)
            if response.status_code == 200:
                data = response.json()
                if "choices" in data:
                    return data["choices"][0]["message"]['content']
                else:
                    print("Неожиданная структура ответа:", data)
                    return None
            else:
                print(f"Ошибка Mistral API: {response.status_code}, {response.text}")
                return None
        except requests.exceptions.RequestException as e:
            print(f"Сетевая ошибка: {e}")
            return None