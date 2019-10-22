import os

class Config(object):
    # get a token from @BotFather
    TG_BOT_TOKEN = os.environ.get('TG_BOT_TOKEN', None)
    # required for running on Heroku
    URL = os.environ.get('URL', "")
    PORT = int(os.environ.get('PORT', 5000))
    # Python3 ReQuests CHUNK SIZE
    CHUNK_SIZE = 10280


class Development(Config):
    pass
