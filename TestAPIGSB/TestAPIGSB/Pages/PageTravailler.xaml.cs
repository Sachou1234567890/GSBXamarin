
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
    public partial class PageTravailler : ContentPage
    {
        public PageTravailler()
        {
            InitializeComponent();
        }
        HttpClient ws;
        //au chargement
        protected override async void OnAppearing()
        {
            base.OnAppearing();
            List<Travailler> lesTravaux = new List<Travailler>();

            ws = new HttpClient();
            var reponse = await ws.GetAsync("http://10.0.2.2/SIO2ALT/APIGSB/travailler/");
            var content = await reponse.Content.ReadAsStringAsync();
            lesTravaux = JsonConvert.DeserializeObject<List<Travailler>>(content);
            lvTravailler.ItemsSource = lesTravaux;
        }
    }
}
