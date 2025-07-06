using ConsoleChatting.classes;
using System.Net;

namespace ConsoleChatting
{
    public class Program
    {
        private static Program app = new Program();
        Scenario scenario = new Scenario();

        static void Main(string[] args)
        {
            //app.startScene();
            app.scenario.startScene();
        }
    }
}
