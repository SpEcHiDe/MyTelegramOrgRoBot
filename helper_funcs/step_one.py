#!/usr/bin/env python
# -*- coding: utf-8 -*-
# (c) Shrimadhav U K


import logging
import requests

# Enable logging
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)

logger = logging.getLogger(__name__)


def request_tg_code_get_random_hash(input_phone_number):
    request_url = "https://my.telegram.org/auth/send_password"
    request_data = {
        "phone": input_phone_number
    }
    response_c = requests.post(request_url, data=request_data)
    json_response = response_c.json()
    return json_response["random_hash"]
