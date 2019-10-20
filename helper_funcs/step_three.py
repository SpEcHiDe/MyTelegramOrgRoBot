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


def scarp_tg_existing_app(stel_token):
    request_url = "https://my.telegram.org/apps"
    custom_header = {
        "Cookie": stel_token
    }
    response_c = requests.get(request_url, headers=custom_header)
    response_text = response_c.text
    # logger.info(response_text)
    soup = BeautifulSoup(response_text, features="html.parser")
    title_of_page = soup.title.string
    if "configuration" in title_of_page:
        # print(soup.prettify())
        g = soup.find_all("span", {"class": "input-xlarge"})
        # App configuration
        app_id = g[0].string
        api_hash = g[1].string
        # Available MTProto servers
        test_configuration = g[4].string
        production_configuration = g[5].string
        # It is forbidden to pass this value to third parties.
        re_dict_vals = {
            "App Configuration": {
                "app_id": app_id,
                "api_hash": api_hash
            },
            "Available MTProto Servers": {
                "test_configuration": test_configuration,
                "production_configuration": production_configuration
            },
            "Disclaimer": "It is forbidden to pass this value to third parties."
        }
        return True, re_dict_vals
    else:
        tg_app_hash = soup.find("input", {"name": "hash"}).get("value")
        re_dict_vals = {
            "tg_app_hash": tg_app_hash
        }
        return False, re_dict_vals
