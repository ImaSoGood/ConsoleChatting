using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleChatting.classes.Objects
{
    public class Message : SystemMessages
    {
        private int id;
        private string message;
        private int user_id;
        private string date;

        public Message(int id, string message, int user_id, string date, bool error, string msg) : base(msg, error)
        {
            this.id = id;
            this.message = message;
            this.user_id = user_id;
            this.date = date;
        }

        public int getId() { return id; }
        public string getMessage() { return message; }
        public int getUser_id() { return user_id; }
        public string getDate() { return date; }
    }
}
