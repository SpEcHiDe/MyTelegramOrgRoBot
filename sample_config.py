import os

class Config(object):
    # get a token from @BotFather
    TG_BOT_TOKEN = os.environ.get('TG_BOT_TOKEN', None)
    # required for running on Heroku
    URL = os.environ.get('URL', "")
    PORT = int(os.environ.get('PORT', 5000))
    # Python3 ReQuests CHUNK SIZE
    CHUNK_SIZE = 10280
    # MyTelegram.org
    # configurtion required while creating new application
    APP_TITLE = os.environ.get("APP_TITLE", "usetgbot")
    APP_SHORT_NAME = os.environ.get("APP_SHORT_NAME", "usetgbot")
    APP_URL = os.environ.get("APP_URL", "https://telegram.dog/UseTGBot")
    APP_DESCRIPTION = os.environ.get("APP_DESCRIPTION", "created using https://telegram.dog/UseTGBot")
    #
    FOOTER_TEXT = os.environ.get("FTEXT", "❤️ @SpEcHlDe")


class Development(Config):
    pass
