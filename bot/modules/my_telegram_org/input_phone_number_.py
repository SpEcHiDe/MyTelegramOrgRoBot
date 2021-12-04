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


from telegram import (
    Update,
    ParseMode
)
from bot import (
    Config,
    GLOBAL_USERS_DICTIONARY,
    INPUT_PHONE_NUMBER,
    INPUT_TG_CODE
)
from bot.helper_funcs.helper_steps import (
    get_phno_imn_ges
)
from bot.helper_funcs.my_telegram_org.step_one import (
    request_tg_code_get_random_hash
)


def input_phone_number(update: Update, context):
    """ ConversationHandler INPUT_PHONE_NUMBER state """
    # info(update)
    user = update.message.from_user
    # info(
    #   "Received Input of %s: %s", user.first_name, update.message.text
    # )
    # receive the phone number entered
    input_text = get_phno_imn_ges(update.message)
    if input_text is None:
        update.message.reply_text(
            text=Config.IN_VALID_PHNO_PVDED,
            parse_mode=ParseMode.HTML
        )
        return INPUT_PHONE_NUMBER
    # try logging in to my.telegram.org/apps
    random_hash = request_tg_code_get_random_hash(input_text)
    GLOBAL_USERS_DICTIONARY.update({
        user.id: {
            "input_phone_number": input_text,
            "random_hash": random_hash
        }
    })
    # save the random hash returned in a dictionary
    # ask user for the **confidential** Telegram code
    update.message.reply_text(
        Config.AFTER_RECVD_CODE_TEXT,
        parse_mode=ParseMode.HTML
    )
    return INPUT_TG_CODE
