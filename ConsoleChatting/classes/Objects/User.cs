using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleChatting.classes
{
    public class User : SystemMessages
    {
        private int Id;
        private string Username;

        public User(int id, string username, string message, bool error) : base(message, error)
        {
            this.Id = id;
            this.Username = username;
        }

        public int getId() 
        {
            return Id;
        }

        public string getUsername() 
        {
            return Username;
        }
    }
}
