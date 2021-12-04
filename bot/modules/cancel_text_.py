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
    Update
)
from bot import (
    Config,
    ConversationHandler
)


def cancel(update: Update, context):
    """ ConversationHandler /cancel state """
    # user = update.message.from_user
    # info("User %s canceled the conversation.", user.first_name)
    update.message.reply_text(Config.CANCELLED_MESG)
    return ConversationHandler.END


def error(update: Update, context):
    """Log Errors caused by Updates."""
    print("Update %s caused error %s", update, context.error)
