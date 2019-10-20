import os

class Config(object):
    # get a token from @BotFather
    TG_BOT_TOKEN = os.environ.get('TG_BOT_TOKEN', None)
    # required for running on Heroku
    URL = os.environ.get('URL', "")
    PORT = int(os.environ.get('PORT', 5000))
    # get a token from ChatBase.com for analytics
    CBTOKEN = os.environ.get('CBTOKEN', None)
    # Python3 ReQuests CHUNK SIZE
    CHUNK_SIZE = 10280
    # specify download directory
    DOWNLOAD_DIRECTORY = os.environ.get("DOWNLOAD_DIRECTORY", "./DOWNLOADS/")


class Development(Config):
    pass
