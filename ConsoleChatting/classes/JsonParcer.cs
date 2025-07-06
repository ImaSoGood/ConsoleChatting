using ConsoleChatting.classes.Objects;
using System;
using System.Text.Json;
using System.Text.Json.Nodes;

namespace ConsoleChatting.classes
{
    public class JsonParcer
    {
        public JsonParcer() { }

        public User parceJsonUser(string json)
        {
            JsonNode data = JsonNode.Parse(json);

            string message = (string)data["message"];
            bool error = (bool)data["error"];
            int id = 0;
            string username = "";

            if (!error)
            {
                username = (string)data["username"];
                id = (int)data["id"];
            }
            
            return new User(id, username, message, error);
        }

        public Chat parceJsonChat(string json) 
        {
            JsonNode data = JsonNode.Parse(json);

            string message = (string)data["message"];
            bool error = (bool)data["error"];
            int chat_id = 0;
            int user1_id = 0;
            int user2_id = 0;

            if (!error)
            {
                chat_id = (int)data["chat_id"];
            }

            return new Chat(chat_id, 0, 0, message, error);
        }

        public List<Message> ParseJsonMessages(string json)
        {
            JsonNode data = JsonNode.Parse(json);
            bool error = (bool)data["error"];
            var messages = new List<Message>();

            if (!error && data["messages"] is JsonArray messagesArray)
            {
                foreach (var messageNode in messagesArray)
                {
                    int id = (int)messageNode["id"];
                    int userId = (int)messageNode["user_id"];
                    string messageText = (string)messageNode["message"];
                    string date = (string)messageNode["date"];
                    int chatId = (int)messageNode["chat_id"];

                    messages.Add(new Message(id, messageText, userId, date, error, messageText));
                }
            }

            return messages;
        }

        public bool parceSendMessage(string json) 
        {
            JsonNode data = JsonNode.Parse(json);

            bool error = (bool)data["error"];
            if (!error)
            {
                return true;
            }
            else 
            {
                Console.WriteLine((string)data["message"]);
                return false;
            }
        }

        public List<ChatList> parceChatsList(string json) 
        {
            JsonNode data = JsonNode.Parse(json);
            bool error = (bool)data["error"];
            var chats = new List<ChatList>();

            if (!error && data["chats"] is JsonArray chatsList) 
            {
                foreach (var chatNode in chatsList) 
                {
                    int chat_id = (int)chatNode["chat_id"];
                    int user_id = (int)chatNode["user_id"];
                    string username = (string)chatNode["username"];

                    chats.Add(new ChatList(chat_id, user_id, username));
                }
            }

            return chats;
        }
    }
}
