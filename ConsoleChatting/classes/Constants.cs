using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleChatting.classes
{
    public class Constants
    {
        private const string URL = "http://hundredtries.online/v1/";

        private string[] URLS = new string[]
        {
            URL + "registerUser.php",
            URL + "loginUser.php",
            URL + "CreateChat.php",
            URL + "GetMessages.php",
            URL + "SendMessage.php",
            URL + "FindUserChats.php",
        };

        private List<string[]> URL_DATA = new List<string[]>()
        {
            new string[]{ "username", "password" },
            new string[]{ "username", "password" },
            new string[]{ "user_id", "username" }, 
            new string[]{ "chat_id", "message_id" },
            new string[]{ "user_id", "message", "chat_id" },
            new string[]{ "user_id" },
        };

        public string SEND_URL = "";
        public Dictionary<string, string> URL_PARAMS = new Dictionary<string, string>();

        public Constants()
        {

        }

        public Constants getData(int number, string[] url_params)
        {
            Constants data = new Constants();

            data.SEND_URL = URLS[number];
            data.URL_PARAMS = PrepareDictionary(number, url_params);

            return data;
        }

        public Dictionary<string, string> PrepareDictionary(int num, string[] url_params)
        {
            for (int i = 0; i <= URL_DATA[num].Length - 1; i++)
            {
                URL_PARAMS.Add(URL_DATA[num][i], url_params[i]);
            }

            return URL_PARAMS;
        }

    }
}
