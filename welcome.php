<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Symptom Analyzer</title>
    <!-- Add Marked.js for Markdown parsing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/4.3.0/marked.min.js"></script>
    <style>
        :root {
            --primary-color: #4285F4;
            --secondary-color: #34A853;
            --background-color: #f5f7fa;
            --card-color: #ffffff;
            --text-color: #333333;
            --error-color: #EA4335;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            margin: 0;
            padding: 0;
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .description {
            color: #666;
            margin-bottom: 20px;
        }

        .form-card {
            background-color: var(--card-color);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            font-family: inherit;
            box-sizing: border-box;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            width: 100%;
            font-weight: 600;
        }

        button:hover {
            background-color: #3367d6;
        }

        button:disabled {
            background-color: #a1a1a1;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .response-card {
            background-color: var(--card-color);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-top: 30px;
            display: none;
        }

        .response-content {
            line-height: 1.7;
        }

        /* Markdown styling */
        .markdown-content h1 {
            font-size: 1.8em;
            margin-top: 1em;
            margin-bottom: 0.5em;
            color: var(--primary-color);
        }

        .markdown-content h2 {
            font-size: 1.5em;
            margin-top: 1em;
            margin-bottom: 0.5em;
            color: var(--secondary-color);
        }

        .markdown-content h3 {
            font-size: 1.3em;
            margin-top: 0.8em;
            margin-bottom: 0.4em;
        }

        .markdown-content ul, .markdown-content ol {
            padding-left: 2em;
            margin-bottom: 1em;
        }

        .markdown-content li {
            margin-bottom: 0.5em;
        }

        .markdown-content p {
            margin-bottom: 1em;
        }

        .markdown-content blockquote {
            border-left: 4px solid #ddd;
            padding-left: 1em;
            margin-left: 0;
            color: #666;
        }

        .markdown-content code {
            background-color: #f0f0f0;
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-family: monospace;
        }

        .markdown-content pre {
            background-color: #f5f5f5;
            padding: 1em;
            border-radius: 5px;
            overflow-x: auto;
        }

        .markdown-content pre code {
            background-color: transparent;
            padding: 0;
        }

        .disclaimer {
            margin-top: 20px;
            padding: 12px;
            background-color: #fff8e1;
            border-left: 4px solid #ffca28;
            border-radius: 4px;
            font-size: 14px;
        }

        .error-message {
            color: var(--error-color);
            margin-top: 10px;
            display: none;
            font-weight: 500;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px 0;
            color: #666;
            font-size: 14px;
        }
        .auth-buttons {
            margin-top: 15px;
        }

        .auth-btn {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .login-btn {
            background-color: var(--primary-color);
            color: white;
        }

        .login-btn:hover {
            background-color: #3367d6;
        }

        .logout-btn {
            background-color: var(--error-color);
            color: white;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
            
            .form-card, .response-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <div class="container">
        <header>
            <h1>Medical Symptom Analyzer</h1>
            <p class="description">Enter your symptoms for AI-powered analysis and home remedy suggestions</p>
            <div class="auth-buttons">
                <?php 
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
                    echo '<a href="logout.php" class="auth-btn logout-btn">Logout</a>';
                } else {
                    echo '<a href="login.php" class="auth-btn login-btn">Login</a>';
                }
                ?>
            </div>
        </header>

        <div class="form-card">
            <form id="symptomForm">
                <div class="form-group">
                    <label for="symptom">Symptom Description:</label>
                    <textarea id="symptom" placeholder="Describe your symptoms in detail..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" min="1" max="120" placeholder="Enter your age" required>
                </div>
                
                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <select id="sex" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <button type="submit" id="analyze-btn">Analyze Symptoms</button>
                <p class="error-message" id="error-message"></p>
            </form>
        </div>
        
        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Analyzing your symptoms...</p>
        </div>
        
        <div class="response-card" id="response-card">
            <h2>Analysis Results</h2>
            <div class="response-content markdown-content" id="response-content"></div>
            
            <div class="disclaimer">
                <strong>Important:</strong> This tool uses AI to provide general information and is not a substitute for professional medical advice. Always consult with a healthcare provider for proper diagnosis and treatment.
            </div>
        </div>
    </div>
    
    <footer>
        <p>© 2025 Medical Symptom Analyzer | For informational purposes only</p>
    </footer>

    <script>
        const apiKey="AIzaSyDzVVBKx-AqH5AqyUcpEFQ2rpvUMBigIWg";
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('symptomForm');
            const loadingElement = document.getElementById('loading');
            const responseCard = document.getElementById('response-card');
            const responseContent = document.getElementById('response-content');
            const errorMessage = document.getElementById('error-message');
            
            // Configure marked options for better security
            marked.setOptions({
                headerIds: false,
                mangle: false
            });
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Get form values
                const symptom = document.getElementById('symptom').value;
                const age = document.getElementById('age').value;
                const sex = document.getElementById('sex').value;
                
                // Hide any previous error messages
                errorMessage.style.display = 'none';
                
                // Validate inputs
                if (!symptom || !age || !sex ) {
                    showError('Please fill in all required fields');
                    return;
                }
                
                // Show loading spinner
                loadingElement.style.display = 'block';
                responseCard.style.display = 'none';
                
                // Format the input for the API
                const formattedInput = `Symptom: ${symptom}\nAge: ${age}\nSex: ${sex}`;
                
                try {
                    const response = await callGeminiAPI(formattedInput, apiKey);
                    
                    // Hide loading spinner
                    loadingElement.style.display = 'none';
                    
                    // Convert response to Markdown and display
                    responseContent.innerHTML = marked.parse(response);
                    responseCard.style.display = 'block';
                    
                    // Scroll to response
                    responseCard.scrollIntoView({ behavior: 'smooth' });
                } catch (error) {
                    // Hide loading spinner
                    loadingElement.style.display = 'none';
                    
                    // Show error message
                    showError(error.message || 'Failed to analyze symptoms. Please try again.');
                }
            });
            
            async function callGeminiAPI(userInput, apiKey) {
                const apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-thinking-exp-01-21:generateContent';
                
                const systemPrompt = `You are a helpful and knowledgeable medical symptom analyzer and home remedy advisor. Your goal is to review user inputs provided in the following exact format: Symptom: <description> Age: <user's age> Sex: <user's sex> Using the information provided: 1. Identify the symptom and determine what might be happening based on the description. 2. Provide possible causes or conditions related to the symptom. 3. Offer safe, age-appropriate home remedy suggestions, focusing on natural or common household approaches. 4. Clearly state any precautions and advise the user to consult with a medical professional for an accurate diagnosis. 5. Include the following disclaimer in every answer: "Note: I am not a licensed medical professional. This advice is informational only and should not be considered a substitute for professional medical care." Your output should be structured to first acknowledge the symptom and its potential implications, then clearly list possible conditions and provide advice for home remedies, followed by the disclaimer. Do not mention any specific medications or treatments without recommending professional follow-up. IMPORTANT: Format your response using Markdown for better readability. Use headings (# for main headings, ## for subheadings), bullet points (- or *), and other Markdown elements as needed.`;
                
                const requestData = {
                    contents: [
                        {
                            role: "user",
                            parts: [
                                { text: systemPrompt },
                                { text: userInput }
                            ]
                        }
                    ],
                    generationConfig: {
                        temperature: 0.4,
                        topP: 0.95,
                        topK: 40,
                        maxOutputTokens: 1024
                    }
                };
                
                try {
                    const response = await fetch(`${apiUrl}?key=${apiKey}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(requestData)
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error?.message || 'API request failed');
                    }
                    
                    const data = await response.json();
                    
                    if (data.candidates && data.candidates[0] && data.candidates[0].content && 
                        data.candidates[0].content.parts && data.candidates[0].content.parts[0]) {
                        return data.candidates[0].content.parts[0].text;
                    } else {
                        throw new Error('Invalid response format from API');
                    }
                } catch (error) {
                    console.error('API call error:', error);
                    throw error;
                }
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>