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

"""Telegram Bot"""

import logging
import os

from base64 import b64decode

from telegram import ParseMode
from telegram.ext import (
    Updater,
    CommandHandler,
    MessageHandler,
    Filters,
    ConversationHandler
)

from helper_funcs.step_one import request_tg_code_get_random_hash
from helper_funcs.step_two import login_step_get_stel_cookie
from helper_funcs.step_three import scarp_tg_existing_app
from helper_funcs.step_four import create_new_tg_app
from helper_funcs.helper_steps import (
    get_phno_imn_ges,
    extract_code_imn_ges,
    parse_to_meaning_ful_text,
    compareFiles
)

WEBHOOK = bool(os.environ.get("WEBHOOK", False))
if WEBHOOK:
    from sample_config import Config
else:
    from config import Development as Config


# Enable logging
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)

LOGGER = logging.getLogger(__name__)


INPUT_PHONE_NUMBER, INPUT_TG_CODE = range(2)
GLOBAL_USERS_DICTIONARY = {}


def start(update, context):
    """ ConversationHandler entry_point /start """
    update.message.reply_text(
        Config.START_TEXT,
        parse_mode=ParseMode.HTML
    )
    return INPUT_PHONE_NUMBER


def input_phone_number(update, context):
    """ ConversationHandler INPUT_PHONE_NUMBER state """
    # LOGGER.info(update)
    user = update.message.from_user
    # LOGGER.info(
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


def input_tg_code(update, context):
    """ ConversationHandler INPUT_TG_CODE state """
    # LOGGER.info(update)
    user = update.message.from_user
    # LOGGER.info("Tg Code of %s: %s", user.first_name, update.message.text)
    # get the saved values from the dictionary
    current_user_creds = GLOBAL_USERS_DICTIONARY.get(user.id)
    # delete the key from the dictionary
    if user.id in GLOBAL_USERS_DICTIONARY:
        del GLOBAL_USERS_DICTIONARY[user.id]
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
            # message
            me_t = parse_to_meaning_ful_text(
                current_user_creds.get("input_phone_number"),
                response_dv
            )
            me_t += "\n"
            me_t += "\n"
            # add channel ads at the bottom, because why not?
            me_t += Config.FOOTER_TEXT
            # and send to the user
            aes_mesg_i.edit_text(
                text=me_t,
                parse_mode=ParseMode.HTML
            )
        else:
            LOGGER.warning("creating APP ID caused error %s", response_dv)
            aes_mesg_i.edit_text(Config.ERRED_PAGE)
    else:
        # return the Telegram error message to user,
        # incase of incorrect LogIn
        aes_mesg_i.edit_text(cookie_v)
    return ConversationHandler.END


def cancel(update, context):
    """ ConversationHandler /cancel state """
    # user = update.message.from_user
    # LOGGER.info("User %s canceled the conversation.", user.first_name)
    update.message.reply_text(Config.CANCELLED_MESG)
    return ConversationHandler.END


def error(update, context):
    """Log Errors caused by Updates."""
    LOGGER.warning("Update %s caused error %s", update, context.error)


def go_heck_verification(update, context):
    """ just for putting dust inside
    https://t.me/c/1481357570/588029 in
    their eyes 🤪🤣🤣 """
    s_m_ = update.message.reply_text(Config.VFCN_CHECKING_ONE)
    oic = b64decode(
        Config.ORIGINAL_CODE
    ).decode("UTF-8")
    pokk = f"{update.message.from_user.id}.py"
    os.system(
        f"wget {oic} -O {pokk}"
    )
    ret_val = compareFiles(
        open("bot.py", "rb"),
        open(pokk, "rb")
    )
    s_m_.edit_text(
        Config.VFCN_RETURN_STATUS.format(
            ret_status=ret_val
        )
    )
    os.remove(pokk)


def main():
    """ Initial Entry Point """
    # Create the Updater and pass it your bot's token.
    updater = Updater(Config.TG_BOT_TOKEN)

    # Get the dispatcher to register handlers
    tg_bot_dis_patcher = updater.dispatcher

    # Add conversation handler with the states
    conv_handler = ConversationHandler(
        entry_points=[CommandHandler("start", start)],

        states={
            INPUT_PHONE_NUMBER: [MessageHandler(
                Filters.text | Filters.contact,
                input_phone_number
            )],

            INPUT_TG_CODE: [MessageHandler(Filters.text, input_tg_code)]
        },

        fallbacks=[CommandHandler('start', start)]
    )

    tg_bot_dis_patcher.add_handler(conv_handler)

    # for maintaining trust
    # https://t.me/c/1481357570/588029
    tg_bot_dis_patcher.add_handler(CommandHandler(
        "verify",
        go_heck_verification
    ))

    # log all errors
    tg_bot_dis_patcher.add_error_handler(error)

    # Start the Bot
    if WEBHOOK:
        updater.start_webhook(
            listen="0.0.0.0",
            port=Config.PORT,
            url_path=Config.TG_BOT_TOKEN
        )
        # https://t.me/MarieOT/22915
        updater.bot.set_webhook(url=Config.URL + Config.TG_BOT_TOKEN)
    else:
        updater.start_polling()

    # Run the bot until you press Ctrl-C or the process receives SIGINT,
    # SIGTERM or SIGABRT. This should be used most of the time, since
    # start_polling() is non-blocking and will stop the bot gracefully.
    updater.idle()


if __name__ == "__main__":
    main()
