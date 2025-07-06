using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleChatting.classes
{
    public class Requests
    {
        public Requests()
        {

        }

        public async Task<string> sendRequest(int num, string[] param)
        {
            ServicePointManager.SecurityProtocol = SecurityProtocolType.Tls12;

            Constants constants = new Constants();
            constants = constants.getData(num, param);

            var pairs = constants.URL_PARAMS;

            using (var httpClient = new HttpClient())
            {
                try
                {
                    var content = new FormUrlEncodedContent(pairs);

                    var response = await httpClient.PostAsync(constants.SEND_URL, content);
                    var responseString = await response.Content.ReadAsStringAsync();

                    return responseString;
                }
                catch (Exception ex)
                {
                    Console.WriteLine($"Error: {ex.Message}");
                }
            }

            return "json";
        }
    }
}
