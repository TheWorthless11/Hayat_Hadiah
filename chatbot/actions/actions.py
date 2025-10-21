from typing import Any, Text, Dict, List
from rasa_sdk import Action, Tracker
from rasa_sdk.executor import CollectingDispatcher
import requests
from datetime import datetime

# Base URL for Laravel API
LARAVEL_API_BASE = "http://localhost/Hayat_Hadi'ah/public/api"


class ActionGetPrayerTimes(Action):
    def name(self) -> Text:
        return "action_get_prayer_times"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        # Get user location from slot or default
        location = tracker.get_slot("user_location")
        
        try:
            # Call Laravel API
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/prayer-times", 
                                  params={"location": location})
            
            if response.status_code == 200:
                data = response.json()
                
                # Format prayer times message
                message = f"🕌 **Prayer Times for {data.get('location', 'your location')}**\n"
                message += f"📅 {data.get('date', 'Today')}\n\n"
                
                for prayer in data.get('prayer_times', []):
                    # Extract just the time (HH:MM) from the datetime string
                    time_str = prayer['time']
                    if 'T' in time_str:
                        # Format: 2025-10-19T04:50:00.000000Z -> 04:50
                        time_part = time_str.split('T')[1].split(':')
                        formatted_time = f"{time_part[0]}:{time_part[1]}"
                    else:
                        formatted_time = time_str
                    
                    message += f"• {prayer['name']}: {formatted_time}\n"
                
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't fetch prayer times right now. Please try again later.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error getting prayer times. Please try again.")
        
        return []


class ActionGetSpecificPrayer(Action):
    def name(self) -> Text:
        return "action_get_specific_prayer"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        # Get prayer name from entity
        prayer_name = next(tracker.get_latest_entity_values("prayer_name"), None)
        location = tracker.get_slot("user_location")
        
        if not prayer_name:
            dispatcher.utter_message(text="Which prayer time would you like to know? (Fajr, Dhuhr, Asr, Maghrib, Isha)")
            return []
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/prayer-times/{prayer_name}", 
                                  params={"location": location})
            
            if response.status_code == 200:
                data = response.json()
                
                # Extract just the time (HH:MM) from datetime string
                time_str = data['time']
                if 'T' in time_str:
                    time_part = time_str.split('T')[1].split(':')
                    formatted_time = f"{time_part[0]}:{time_part[1]}"
                else:
                    formatted_time = time_str
                
                message = f"🕌 {data['prayer_name']} prayer time is at {formatted_time}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text=f"Sorry, I couldn't find the time for {prayer_name} prayer.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetQiblaDirection(Action):
    def name(self) -> Text:
        return "action_get_qibla_direction"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        location = tracker.get_slot("user_location")
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/qibla-direction", 
                                  params={"location": location})
            
            if response.status_code == 200:
                data = response.json()
                message = f"🧭 The Qibla direction from your location is {data['direction']}° from North.\n\n"
                message += f"Distance to Kaaba: {data['distance']} km"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't determine the Qibla direction right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetQuranVerseByTopic(Action):
    def name(self) -> Text:
        return "action_get_quran_verse_by_topic"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        topic = next(tracker.get_latest_entity_values("quran_topic"), None)
        
        if not topic:
            topic = "random"
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/quran-verse", 
                                  params={"topic": topic})
            
            if response.status_code == 200:
                data = response.json()
                message = f"📖 **{data['surah_name']} ({data['surah_number']}:{data['verse_number']})**\n\n"
                message += f"_{data['arabic']}_\n\n"
                message += f"{data['translation']}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't find a verse on that topic.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetRandomQuranVerse(Action):
    def name(self) -> Text:
        return "action_get_random_quran_verse"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/quran-verse/random")
            
            if response.status_code == 200:
                data = response.json()
                message = f"📖 **{data['surah_name']} ({data['surah_number']}:{data['verse_number']})**\n\n"
                message += f"_{data['arabic']}_\n\n"
                message += f"{data['translation']}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't retrieve a verse right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetHadithByTopic(Action):
    def name(self) -> Text:
        return "action_get_hadith_by_topic"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        topic = next(tracker.get_latest_entity_values("hadith_topic"), None)
        
        if not topic:
            topic = "random"
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/hadith", 
                                  params={"topic": topic})
            
            if response.status_code == 200:
                data = response.json()
                message = f"📚 **Hadith ({data['collection']})**\n\n"
                message += f"_{data['arabic']}_\n\n"
                message += f"{data['translation']}\n\n"
                message += f"Reference: {data['reference']}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't find a hadith on that topic.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetRandomHadith(Action):
    def name(self) -> Text:
        return "action_get_random_hadith"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/hadith/random")
            
            if response.status_code == 200:
                data = response.json()
                message = f"📚 **Hadith ({data['collection']})**\n\n"
                message += f"_{data['arabic']}_\n\n"
                message += f"{data['translation']}\n\n"
                message += f"Reference: {data['reference']}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't retrieve a hadith right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetDuas(Action):
    def name(self) -> Text:
        return "action_get_duas"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/duas")
            
            if response.status_code == 200:
                data = response.json()
                message = "🤲 **Daily Duas**\n\n"
                
                for dua in data.get('duas', [])[:5]:  # Show first 5
                    message += f"• {dua['title']}\n"
                
                message += "\nWould you like to see a specific dua category?"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't retrieve duas right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetSpecificDua(Action):
    def name(self) -> Text:
        return "action_get_specific_dua"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        category = next(tracker.get_latest_entity_values("dua_category"), None)
        
        if not category:
            dispatcher.utter_message(text="Which dua category would you like? (Morning, Evening, Travel, etc.)")
            return []
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/duas/{category}")
            
            if response.status_code == 200:
                data = response.json()
                message = f"🤲 **{data['title']}**\n\n"
                message += f"_{data['arabic']}_\n\n"
                message += f"{data['transliteration']}\n\n"
                message += f"**Translation:** {data['translation']}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text=f"Sorry, I couldn't find duas for {category}.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetZakatInfo(Action):
    def name(self) -> Text:
        return "action_get_zakat_info"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/zakat-info")
            
            if response.status_code == 200:
                data = response.json()
                message = "💰 **Zakat Information**\n\n"
                message += f"{data['description']}\n\n"
                message += f"**Nisab:** {data['nisab']}\n"
                message += f"**Rate:** {data['rate']}\n\n"
                message += "Would you like to calculate your Zakat?"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't retrieve Zakat information right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionCalculateZakat(Action):
    def name(self) -> Text:
        return "action_calculate_zakat"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        message = "To calculate your Zakat, please visit our Zakat calculator page where you can:\n\n"
        message += "• Enter your wealth details\n"
        message += "• View Nisab thresholds\n"
        message += "• Get accurate Zakat calculation\n"
        message += "• Pay Zakat directly if you wish\n\n"
        message += f"Visit: {LARAVEL_API_BASE.replace('/api', '')}/zakat"
        
        dispatcher.utter_message(text=message)
        
        return []


class ActionGetDonationInfo(Action):
    def name(self) -> Text:
        return "action_get_donation_info"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/donations/info")
            
            if response.status_code == 200:
                data = response.json()
                message = "❤️ **Donation Categories**\n\n"
                
                for category in data.get('categories', []):
                    message += f"• **{category['name']}**: {category['description']}\n"
                    if category.get('goal_amount'):
                        message += f"  Goal: ₹{category['goal_amount']} ({category['progress']}% reached)\n"
                    message += "\n"
                
                message += "Would you like to make a donation?"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't retrieve donation information right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionInitiateDonation(Action):
    def name(self) -> Text:
        return "action_initiate_donation"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        category = next(tracker.get_latest_entity_values("donation_category"), None)
        
        message = "To make a donation, please visit our donation page:\n\n"
        
        if category:
            message += f"I'll help you donate to {category}.\n\n"
        
        message += f"Visit: {LARAVEL_API_BASE.replace('/api', '')}/donations\n\n"
        message += "You can:\n"
        message += "• Choose a donation category\n"
        message += "• Enter your donation amount\n"
        message += "• Pay via bKash or Nagad\n"
        message += "• Receive email confirmation\n\n"
        message += "JazakAllah Khair for your generosity! 🤲"
        
        dispatcher.utter_message(text=message)
        
        return []


class ActionFindMosques(Action):
    def name(self) -> Text:
        return "action_find_mosques"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        location = tracker.get_slot("user_location")
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/mosques/nearby", 
                                  params={"location": location})
            
            if response.status_code == 200:
                data = response.json()
                message = "🕌 **Nearby Mosques**\n\n"
                
                for mosque in data.get('mosques', [])[:5]:
                    message += f"• **{mosque['name']}**\n"
                    message += f"  Distance: {mosque['distance']} km\n"
                    message += f"  Address: {mosque['address']}\n\n"
                
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't find nearby mosques right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetFastingTimes(Action):
    def name(self) -> Text:
        return "action_get_fasting_times"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        location = tracker.get_slot("user_location")
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/fasting-times", 
                                  params={"location": location})
            
            if response.status_code == 200:
                data = response.json()
                message = f"🌙 **Fasting Times for {data['date']}**\n\n"
                message += f"• **Suhoor ends:** {data['suhoor_end']}\n"
                message += f"• **Iftar time:** {data['iftar_time']}\n\n"
                message += f"Fasting duration: {data['duration']}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't fetch fasting times right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []


class ActionGetRamadanInfo(Action):
    def name(self) -> Text:
        return "action_get_ramadan_info"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        
        try:
            response = requests.get(f"{LARAVEL_API_BASE}/chatbot/ramadan-info")
            
            if response.status_code == 200:
                data = response.json()
                message = f"🌙 **Ramadan {data['year']}**\n\n"
                message += f"• Starts: {data['start_date']}\n"
                message += f"• Ends: {data['end_date']}\n"
                message += f"• Days remaining: {data['days_remaining']}\n\n"
                message += f"{data['message']}"
                dispatcher.utter_message(text=message)
            else:
                dispatcher.utter_message(text="Sorry, I couldn't retrieve Ramadan information right now.")
        
        except Exception as e:
            dispatcher.utter_message(text="Sorry, there was an error. Please try again.")
        
        return []
