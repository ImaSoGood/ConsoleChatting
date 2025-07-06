using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleChatting.classes.Objects
{
    public class Chat : SystemMessages
    {
        private int chat_id;
        private int user1_id;
        private int user2_id;
        private string username;

        public Chat(int id, int user1_id, int user2_id, string message, bool error) : base(message, error) 
        {
            this.chat_id = id;
            this.user1_id = user1_id;
            this.user2_id = user2_id;
        }

        public int getChat_id() 
        {
            return chat_id;
        }

        public void setUsername(string username) 
        {
            this.username = username;
        }

        public string getUsername() 
        {
            return this.username;
        }
    }
}
