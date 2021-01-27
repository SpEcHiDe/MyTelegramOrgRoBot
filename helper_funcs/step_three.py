#!/usr/bin/env python
# -*- coding: utf-8 -*-
# Copyright (c) Shrimadhav U K
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <https://www.gnu.org/licenses/>.

""" STEP THREE """

import requests
from bs4 import BeautifulSoup


def scarp_tg_existing_app(stel_token):
    """scraps the web page using the provided cookie,
    returns True or False appropriately"""
    request_url = "https://my.telegram.org/apps"
    custom_header = {
        "Cookie": stel_token
    }
    response_c = requests.get(request_url, headers=custom_header)
    response_text = response_c.text
    # print(response_text)
    soup = BeautifulSoup(response_text, features="html.parser")
    title_of_page = soup.title.string
    #
    re_dict_vals = {}
    re_status_id = None
    if "configuration" in title_of_page:
        # print(soup.prettify())
        g_inputs = soup.find_all("span", {"class": "input-xlarge"})
        # App configuration
        app_id = g_inputs[0].string
        api_hash = g_inputs[1].string
        # Available MTProto servers
        test_configuration = g_inputs[4].string
        production_configuration = g_inputs[5].string
        # It is forbidden to pass this value to third parties.
        _a = "It is forbidden to pass this value to third parties."
        #
        hi_inputs = soup.find_all("p", {"class": "help-block"})
        test_dc = hi_inputs[-2].text.strip()
        production_dc = hi_inputs[-1].text.strip()
        re_dict_vals = {
            "App Configuration": {
                "app_id": app_id,
                "api_hash": api_hash
            },
            "Available MTProto Servers": {
                "test_configuration": {
                    "IP": test_configuration,
                    "DC": test_dc
                },
                "production_configuration": {
                    "IP": production_configuration,
                    "DC": production_dc
                }
            },
            "Disclaimer": _a
        }
        #
        re_status_id = True
    else:
        tg_app_hash = soup.find("input", {"name": "hash"}).get("value")
        re_dict_vals = {
            "tg_app_hash": tg_app_hash
        }
        re_status_id = False
    return re_status_id, re_dict_vals
