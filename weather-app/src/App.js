import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './App.css';

const App = () => {
  const [city, setCity] = useState('');
  const [weather, setWeather] = useState(null);
  const [error, setError] = useState(null);
  const [suggestions, setSuggestions] = useState([]);
  const [loading, setLoading] = useState(false);
  const [debouncedCity, setDebouncedCity] = useState(city);

  const apiKey = '46cdceda898f4e99c54e2898f620c5d8';  
  
  useEffect(() => {
    const timer = setTimeout(() => {
      setDebouncedCity(city);
    }, 500);  
    return () => clearTimeout(timer); 
  }, [city]);

  useEffect(() => {
    if (!debouncedCity) {
      setSuggestions([]);
      return;
    }
    const getCitySuggestions = async () => {
      try {
        const response = await axios.get(
          `https://api.openweathermap.org/data/2.5/find?q=${debouncedCity}&appid=${apiKey}&lang=tr`
        );
        setSuggestions(response.data.list || []);
      } catch (err) {
        console.error("Şehir önerileri alınırken bir hata oluştu:", err);
      }
    };
    getCitySuggestions();
  }, [debouncedCity]);

  const getWeather = async () => {
    if (!city) return;
    setLoading(true);
    try {
      const response = await axios.get(
        `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=tr`
      );
      setWeather(response.data);
      setError(null);
      setSuggestions([]); 
    } catch (err) {
      setError('City not found or incorrect API request');
      setWeather(null);
    }
    setLoading(false);
  };

  const handleCityClick = (cityName) => {
    setCity(cityName);
    setSuggestions([]);
  };

  const getBackgroundImage = () => {
    if (!weather) return 'url("/images/default.gif")';  

    const weatherCondition = weather.weather[0].main.toLowerCase();
    const description = weather.weather[0].description.toLowerCase();

    if (weatherCondition === 'rain' || description.includes('rain') || description.includes('shower') || description.includes('drizzle') || description.includes('light')) {
      return 'url("/images/rainy.gif")';  
    }

    switch (weatherCondition) {
      case 'clear':
        return 'url("/images/clear-sky.gif")'; 
      case 'rain':
        return 'url("/images/rainy.gif")'; 
      case 'snow':
        return 'url("/images/snowy.gif")'; 
      case 'clouds':
        return 'url("/images/cloudy.gif")'; 
      case 'fog':
        return 'url("/images/foggy.gif")'; 
      default:
        return 'url("/images/default.gif")'; 
    }
  };

  return (
    <div className="App" style={{ backgroundImage: getBackgroundImage(), backgroundSize: 'cover', minHeight: '100vh' }}>
      <div className="overlay">
        <h1>Hava Durumu Uygulaması</h1>

        <div className="search">
          <input
            type="text"
            value={city}
            onChange={(e) => setCity(e.target.value)}
            placeholder="Şehir ismi girin"
          />
          <button onClick={getWeather}>Hava Durumunu Getir</button>

          {suggestions.length > 0 && (
            <ul className="suggestions">
              {suggestions.map((item) => (
                <li key={item.id} onClick={() => handleCityClick(item.name)}>
                  {item.name}, {item.sys.country} ({item.coord.lat}, {item.coord.lon})
                </li>
              ))}
            </ul>
          )}
        </div>

        {error && <p className="error">{error}</p>}
        {loading && <p>Yükleniyor...</p>}

        {weather && (
          <div className="weather-info">
            <h2>{weather.name}, {weather.sys.country}</h2>
            <p>Sıcaklık: {weather.main.temp}°C</p>
            <p>Nem: {weather.main.humidity}%</p>
            <p>Rüzgar Hızı: {weather.wind.speed} m/s</p>

            <div className="weather-condition">
              <h3>Hava Durumu</h3>
              <p>
                {weather.weather[0].description.charAt(0).toUpperCase() + weather.weather[0].description.slice(1)}
              </p>
              <img
                src={`http://openweathermap.org/img/wn/${weather.weather[0].icon}.png`}
                alt={weather.weather[0].description}
              />
            </div>
          </div>
        )}

        {/**/}
        <button
          className="btn-about"
          onClick={() => alert("Bu uygulama OpenWeatherMap API kullanılarak React ile geliştirilmiştir. Tasarımcı: 2220780042 Yavuz Burak Dikçal")}
        >
          Hakkında
        </button>
      </div>
    </div>
  );
};

export default App;
