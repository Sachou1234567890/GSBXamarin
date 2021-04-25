
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Net.Http;
using TestAPIGSB.ClassesMetier;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace TestAPIGSB.Pages
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class PageStats : ContentPage
    {
        public PageStats()
        {
            InitializeComponent();
        }
        HttpClient ws;
        //au chargement
        protected override async void OnAppearing()
        {
            base.OnAppearing();
            List<Stats> lesStats = new List<Stats>();

            ws = new HttpClient();
            var reponse = await ws.GetAsync("http://10.0.2.2/SIO2ALT/APIGSB/statistique/");
            var content = await reponse.Content.ReadAsStringAsync();
            lesStats = JsonConvert.DeserializeObject<List<Stats>>(content);
            lvStats.ItemsSource = lesStats;
        }
    }
}