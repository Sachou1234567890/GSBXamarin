using Android.Widget;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Text;
using TestAPIGSB.ClassesMetier;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace TestAPIGSB.Pages
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class PageVisiteur : ContentPage
    {
        public PageVisiteur()
        {
            InitializeComponent();
        }

        HttpClient ws;
        //au chargement
        protected override async void OnAppearing()
        {
            base.OnAppearing();
            List<Visiteur> lesVisiteurs = new List<Visiteur>();

            ws = new HttpClient();
            var reponse = await ws.GetAsync("http://10.0.2.2/SIO2ALT/APIGSB/visiteurs/");
            var content = await reponse.Content.ReadAsStringAsync();
            lesVisiteurs = JsonConvert.DeserializeObject<List<Visiteur>>(content);
            lvVisiteurs.ItemsSource = lesVisiteurs;
        }

        private void lvVisiteurs_ItemSelected(object sender, SelectedItemChangedEventArgs e)
        {
            if (lvVisiteurs.SelectedItem != null)
            {
                txtNomVisiteur.Text = (lvVisiteurs.SelectedItem as Visiteur).Nom;
                txtPrenomVisiteur.Text = (lvVisiteurs.SelectedItem as Visiteur).Prenom;
                txtSeccode.Text = ((lvVisiteurs.SelectedItem as Visiteur).Seccode).ToString();
                txtLabcode.Text = ((lvVisiteurs.SelectedItem as Visiteur).Labcode).ToString();
            }
        }

        private async void btnModifier_Clicked(object sender, EventArgs e)
        {
            if (txtNomVisiteur.Text == null)
            {
                Toast.MakeText(Android.App.Application.Context, "Sélectionner un Visiteur", ToastLength.Short).Show();
            }
            else
            {
                ws = new HttpClient();
                Visiteur vis = (lvVisiteurs.SelectedItem as Visiteur);
                vis.Nom = txtNomVisiteur.Text;
                vis.Prenom = txtPrenomVisiteur.Text;
                vis.Seccode = int.Parse(txtSeccode.Text);
                vis.Labcode = int.Parse(txtLabcode.Text);
                JObject jvis = new JObject
                {
                    {"Id",vis.Id},
                    {"Nom",vis.Nom},
                    {"Prenom",vis.Prenom},
                    {"Seccode",vis.Seccode},
                    {"Labcode",vis.Labcode},
                };
                string json = JsonConvert.SerializeObject(jvis);
                StringContent content = new StringContent(json, Encoding.UTF8, "application/json");
                var reponse = await ws.PutAsync("http://10.0.2.2/SIO2ALT/APIGSB/visiteurs/", content);
                List<Visiteur> lesVisiteurs = new List<Visiteur>();

                ws = new HttpClient();
                reponse = await ws.GetAsync("http://10.0.2.2/SIO2ALT/APIGSB/visiteurs/");
                var flux = await reponse.Content.ReadAsStringAsync();
                lesVisiteurs = JsonConvert.DeserializeObject<List<Visiteur>>(flux);
                lvVisiteurs.ItemsSource = lesVisiteurs;
            }
        }

        private async void btnAjouter_Clicked(object sender, EventArgs e)
        {
            if (txtNomVisiteur.Text == null)
            {
                Toast.MakeText(Android.App.Application.Context, "Saisir un nom de Visiteur", ToastLength.Short).Show();
            }
            else
            {
                ws = new HttpClient();
                //Visiteur newVisiteur = new Visiteur();
                //newVisiteur.Visiteur = txtNomVisiteur.Text;
                JObject vis = new JObject
                {
                    { "Vis", txtNomVisiteur.Text},
                    { "Visi", txtPrenomVisiteur.Text},
                    { "Visa", txtSeccode.Text},
                    { "Viso", txtLabcode.Text}
                    
                };
                string json = JsonConvert.SerializeObject(vis);
                StringContent content = new StringContent(json, Encoding.UTF8, "application/json");

                var reponse = await ws.PostAsync("http://10.0.2.2/SIO2ALT/APIGSB/visiteurs/", content);

                List<Visiteur> lesVisiteurs = new List<Visiteur>();

                ws = new HttpClient();
                reponse = await ws.GetAsync("http://10.0.2.2/SIO2ALT/APIGSB/visiteurs/");
                var flux = await reponse.Content.ReadAsStringAsync();
                lesVisiteurs = JsonConvert.DeserializeObject<List<Visiteur>>(flux);
                lvVisiteurs.ItemsSource = lesVisiteurs;
            }
        }
    }
}