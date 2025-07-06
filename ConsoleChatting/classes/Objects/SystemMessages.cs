using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleChatting.classes
{
    public class SystemMessages
    {
        protected string Message { get; }
        protected bool Error { get; }

        public SystemMessages(string message, bool error)
        {
            this.Message = message;
            this.Error = error;
        }

        public string message() 
        {
            return this.Message;
        }

        public bool error() 
        {
            return this.Error;
        }
    }
}
