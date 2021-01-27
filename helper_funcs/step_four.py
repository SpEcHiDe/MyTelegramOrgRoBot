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

""" STEP FOUR """

import random
import requests


def create_new_tg_app(
        stel_token,
        tg_app_hash,
        app_title,
        app_shortname,
        app_url,
        app_platform,
        app_desc
):
    # pylint: disable-msg=too-many-arguments
    """ creates a new my.telegram.org/apps
    using the provided parameters """
    request_url = "https://my.telegram.org/apps/create"
    custom_header = {
        "Cookie": stel_token
    }
    request_data = {
        "hash": tg_app_hash,
        "app_title": app_title,
        "app_shortname": app_shortname,
        "app_url": app_url,
        "app_platform": random.choice(app_platform),
        "app_desc": app_desc
    }
    response_c = requests.post(
        request_url,
        data=request_data,
        headers=custom_header
    )
    return response_c
