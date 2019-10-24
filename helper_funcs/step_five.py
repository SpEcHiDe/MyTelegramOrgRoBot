#!/usr/bin/env python
# -*- coding: utf-8 -*-
# (c) Shrimadhav U K


import logging


# Enable logging
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)

logger = logging.getLogger(__name__)


def parse_to_meaning_ful_text(v):
    me_t = ""
    me_t += "<i>App Configuration</i>"
    me_t += "\n"
    me_t += "<b>APP ID</b>: "
    me_t += "<code>{}</code>".format(v["App Configuration"]["app_id"])
    me_t += "\n"
    me_t += "<b>API HASH</b>: "
    me_t += "<code>{}</code>".format(v["App Configuration"]["api_hash"])
    me_t += "\n"
    me_t += "\n"
    me_t += "<i>Available MTProto Servers</i>"
    me_t += "\n"
    me_t += "<b>Production Configuration</b>: "
    me_t += "<code>{}</code>".format(
        v["Available MTProto Servers"]["production_configuration"]
    )
    me_t += "\n"
    me_t += "<b>Test Configuration</b>: "
    me_t += "<code>{}</code>".format(
        v["Available MTProto Servers"]["test_configuration"]
    )
    return me_t
