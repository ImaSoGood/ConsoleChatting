using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleChatting.classes.Objects
{
    public class ChatList
    {
        private int chat_id;
        private int user_id;
        private string username;

        public ChatList(int chat_id, int user_id, string username)
        {
            this.chat_id = chat_id;
            this.user_id = user_id;
            this.username = username;
        }

        public int getChat_id() 
        {
            return chat_id;
        }

        public int getUser_id() 
        {
            return user_id;
        }

        public string getUsername() 
        {
            return username;
        }

    }
}
