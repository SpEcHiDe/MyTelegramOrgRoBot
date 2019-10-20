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


def login_step_get_stel_cookie(input_phone_number, tg_random_hash, tg_cloud_password):
    request_url = "https://my.telegram.org/auth/login"
    request_data = {
        "phone": input_phone_number,
        "random_hash": tg_random_hash,
        "password": tg_cloud_password
    }
    response_c = requests.post(request_url, data=request_data)
    # logger.info(response_c.text)
    # logger.info(response_c.headers)
    if response_c.text == "true":
        return True, response_c.headers.get("Set-Cookie")
    else:
        return False, response_c.text
