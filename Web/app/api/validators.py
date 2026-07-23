import re

def is_valid_name(name: str): 
    return bool(re.fullmatch(r'^[A-Za-zА-Яа-яёЁ]+$', name))

def is_valid_email(email: str):
    pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
    return bool(re.match(pattern, email))
    
def is_valid_phone(phone: str) -> bool:
    pattern = r'^\+?\d{10,20}$'
    return bool(re.fullmatch(pattern, phone))