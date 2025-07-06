using ConsoleChatting.classes;
using ConsoleChatting.classes.Objects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Timers;

namespace ConsoleChatting
{
    public class Scenario
    {
        private int lastMessage_id;
        private User user;
        private Chat chat;
        private List<Message> messages = new List<Message>();
        private List<ChatList> chatLists = new List<ChatList>();

        System.Timers.Timer timer = new System.Timers.Timer();


        public Scenario() 
        {
            timer = new System.Timers.Timer(1300);
            timer.Elapsed += OnTimedEvent;
        }

        public void startScene()
        {
            Console.Write("You want to register? Answer is y/n: ");
            string answer = Console.ReadLine();
            Console.Clear();

            Console.Write("Enter login: ");
            string login = Console.ReadLine();
            Console.Write("Enter password: ");
            string password = Console.ReadLine();

            Console.Clear();

            if (answer.Equals("y"))
            {
                Console.Write(RegisterScene(new string[] { login, password }));
            }
            else
            {
                Console.Write(LoginScene(new string[] { login, password }));
            }

            Thread.Sleep(2000);
            Console.Clear();
            ShowChats();
        }

        private string RegisterScene(string[] str)
        {
            string answer = new Requests().sendRequest(0, str).Result;
            this.user = new JsonParcer().parceJsonUser(answer);

            if (!user.error())
                Console.ForegroundColor = ConsoleColor.Green;

            return user.message();
        }

        private string LoginScene(string[] str)
        {
            string answer = new Requests().sendRequest(1, str).Result;
            this.user = new JsonParcer().parceJsonUser(answer);

            if (!user.error())
                Console.ForegroundColor = ConsoleColor.Green;

            return user.message();
        }

        private void ChatSelect() 
        {
            if (!this.user.error()) 
            {
                Console.Write("Введите юзернейм для начала диалога: ");
                string username = Console.ReadLine();

                string answer = new Requests()
                    .sendRequest(2, new string[] { this.user.getId().ToString(), username }).Result;
                this.chat = new JsonParcer().parceJsonChat(answer);

                if (!chat.error()) 
                {
                    Console.Clear();
                    chat.setUsername(username);

                    inChat(chat.getChat_id());
                }  
                else
                    Console.WriteLine(chat.message);
            }
        }

        private void inChat(int chat_id)
        {
            timer.Start();
            Console.WriteLine("Вы вошли в чат с " + chat.getUsername() + Environment.NewLine);

            while (true)
            {
                string input = Console.ReadLine();
                Console.SetCursorPosition(0, Console.CursorTop - 1);
                Console.Write(new string(' ', Console.WindowWidth));
                Console.SetCursorPosition(0, Console.CursorTop - 0);

                if (input == "/back")
                {
                    ShowChats();
                }
                else if (!string.IsNullOrWhiteSpace(input))
                {
                    SendMessage(input);
                }

            }

            timer.Stop();
        }

        private void SendMessage(string message) 
        {
            string answer = new Requests()
                .sendRequest(4, new string[] { user.getId().ToString(), message, chat.getChat_id().ToString() })
                    .Result;
            new JsonParcer().parceSendMessage(answer);
        }

        private void ShowChats()
        {
            Console.Clear();
            messages.Clear();
            chatLists.Clear();

            if (!this.user.error())
            {
                Console.WriteLine("Receiving chats data...");
                string answer = new Requests()
                    .sendRequest(5, new string[] { this.user.getId().ToString() }).Result;
                this.chatLists = new JsonParcer().parceChatsList(answer);

                Console.Clear();

                if (chatLists.Count > 0)
                {
                    foreach (ChatList chats in chatLists)
                    {
                        Console.WriteLine("id_" + chats.getChat_id().ToString() + ") " + chats.getUsername());
                    }
                }
                else 
                {
                    Console.WriteLine("No chats :)");
                }

                ChatSelect();
            }
        }

        private void getMessages() 
        {
            string answer = new Requests()
                .sendRequest(3, new string[] { chat.getChat_id().ToString(), lastMessage_id.ToString() }).Result;
            List<Message> msg = new JsonParcer().ParseJsonMessages(answer);

            if (msg.Count > 0) 
            {
                if (messages.Count == 0 || messages[messages.Count - 1].getId() != msg[0].getId())
                {
                    messages.AddRange(msg);
                    lastMessage_id = msg[msg.Count - 1].getId();

                    foreach (Message message in msg)
                    {
                        string person = "";
                        if (message.getUser_id() == user.getId())
                            person = "You";
                        else
                            person = "opponent";

                        Console.WriteLine(person + ") " + message.getMessage());
                    }

                }
            }
                
        }

        private void OnTimedEvent(Object source, ElapsedEventArgs e)
        {
            getMessages();
        }
    }
}
