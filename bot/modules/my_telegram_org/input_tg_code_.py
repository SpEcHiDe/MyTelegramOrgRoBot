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
from telegram.ext import (
    ConversationHandler
)
from bot import (
    Config,
    GLOBAL_USERS_DICTIONARY,
    INPUT_PHONE_NUMBER
)
from bot.helper_funcs.helper_steps import (
    extract_code_imn_ges,
    parse_to_meaning_ful_text
)
from bot.helper_funcs.my_telegram_org.step_two import (
    login_step_get_stel_cookie
)
from bot.helper_funcs.my_telegram_org.step_three import scarp_tg_existing_app
from bot.helper_funcs.my_telegram_org.step_four import create_new_tg_app


def input_tg_code(update: Update, context):
    """ ConversationHandler INPUT_TG_CODE state """
    # info(update)
    user = update.message.from_user
    # info("Tg Code of %s: %s", user.first_name, update.message.text)
    # get the saved values from the dictionary
    current_user_creds = GLOBAL_USERS_DICTIONARY.get(user.id)
    # reply "processing" progress to user
    # we will use this message to edit the status as required, later
    aes_mesg_i = update.message.reply_text(Config.BEFORE_SUCC_LOGIN)
    #
    provided_code = extract_code_imn_ges(update.message)
    if provided_code is None:
        aes_mesg_i.edit_text(
            text=Config.IN_VALID_CODE_PVDED,
            parse_mode=ParseMode.HTML
        )
        return INPUT_PHONE_NUMBER
    # login using provided code, and get cookie
    status_r, cookie_v = login_step_get_stel_cookie(
        current_user_creds.get("input_phone_number"),
        current_user_creds.get("random_hash"),
        provided_code
    )
    if status_r:
        # scrap the my.telegram.org/apps page
        # and check if the user had previously created an app
        status_t, response_dv = scarp_tg_existing_app(cookie_v)
        if not status_t:
            # if not created
            # create an app by the provided details
            create_new_tg_app(
                cookie_v,
                response_dv.get("tg_app_hash"),
                Config.APP_TITLE,
                Config.APP_SHORT_NAME,
                Config.APP_URL,
                Config.APP_PLATFORM,
                Config.APP_DESCRIPTION
            )
        # now scrap the my.telegram.org/apps page
        # it is guranteed that now the user will have an APP ID.
        # if not, the stars have failed us
        # and throw that error back to the user
        status_t, response_dv = scarp_tg_existing_app(cookie_v)
        if status_t:
            # parse the scrapped page into an user readable
            input_phone_number = current_user_creds.get("input_phone_number")
            # message
            me_t = parse_to_meaning_ful_text(
                input_phone_number,
                response_dv
            )
            me_t += "\n"
            GLOBAL_USERS_DICTIONARY.update({
                user.id: {
                    "input_phone_number": input_phone_number,
                    "stea": response_dv
                }
            })
            me_t += "\n"
            # add channel ads at the bottom, because why not?
            me_t += Config.FOOTER_TEXT
            # and send to the user
            aes_mesg_i.edit_text(
                text=me_t,
                parse_mode=ParseMode.HTML
            )
        else:
            # warning("creating APP ID caused error %s", response_dv)
            aes_mesg_i.edit_text(Config.ERRED_PAGE)
    else:
        # return the Telegram error message to user,
        # incase of incorrect LogIn
        aes_mesg_i.edit_text(cookie_v)
    return ConversationHandler.END
