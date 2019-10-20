#!/usr/bin/env python
# -*- coding: utf-8 -*-
# (c) Shrimadhav U K


import logging
import requests
from bs4 import BeautifulSoup


# Enable logging
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)

logger = logging.getLogger(__name__)


def create_new_tg_app(
    stel_token,
    tg_app_hash,
    app_title,
    app_shortname,
    app_url,
    app_platform,
    app_desc
):
    request_url = "https://my.telegram.org/apps/create"
    custom_header = {
        "Cookie": stel_token
    }
    request_data = {
        "hash": tg_app_hash,
        "app_title": app_title,
        "app_shortname": app_shortname,
        "app_url": app_url,
        "app_platform": app_platform,
        "app_desc": app_desc
    }
    response_c = requests.post(
        request_url,
        data=request_data,
        headers=custom_header
    )
    return response_c
