<?php require APPROOT . '/views/coachdash/inc/header.php'; ?>
<div class="kal-coach-profile-manager">
    <!-- Profile Header -->
    <div class="kal-coach-profile-header">
        <h1><?php echo $data['title'] ?? 'Coach Profile Management'; ?></h1>
    <!-- Edit button navigates to dedicated edit page -->
    <button type="button" id="globalEditBtn" class="kal-profile-save-btn" onclick="window.location.href='<?php echo URLROOT; ?>/coachdash/edit'">Edit Profile</button>
    </div>

    <form id="profileForm" action="<?php echo URLROOT; ?>/coachdash/updateProfile" method="POST">
        <div class="kal-profile-layout">
            <!-- Sidebar -->
            <div class="kal-profile-sidebar">











                <!-- Profile Photo Card -->
                <div class="kal-profile-card">
                    <h3>Profile Photo</h3>
                    <div class="kal-profile-photo">
                        <div class="kal-profile-photo-img">
                            <?php if(!empty($data['coach']['image'])): ?>
                                <img src="<?php echo URLROOT . '/' . $data['coach']['image']; ?>" alt="<?php echo htmlspecialchars($data['coach']['name']); ?>">
                            <?php else: ?>
                                <span>No Photo</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p style="color: #888; text-align: center; font-size: 12px; margin-top: 10px;">Click Edit to upload a new photo</p>
                </div>












                <!-- Quick Stats Card -->
                <div class="kal-profile-card">
                    <h3>Profile Stats</h3>
                    <div class="kal-quick-stats">
                        <div class="kal-stat-item">
                            <span class="kal-stat-label">Rating</span>
                            <span class="kal-stat-value"><?php echo $data['coach']['rating']; ?>/5</span>
                        </div>
                        <div class="kal-stat-item">
                            <span class="kal-stat-label">Experience</span>
                            <span class="kal-stat-value"><?php echo $data['coach']['experience']; ?></span>
                        </div>
                        <div class="kal-stat-item">
                            <span class="kal-stat-label">Response Rate</span>
                            <span class="kal-stat-value">95%</span>
                        </div>
                        <div class="kal-stat-item">
                            <span class="kal-stat-label">Students Trained</span>
                            <span class="kal-stat-value">150+</span>
                        </div>
                    </div>
                </div>











                <!-- Availability Card -->
                <div class="kal-profile-card">
                    <h3>Availability</h3>
                    <div class="kal-form-group">
                        <label for="availability">Current Status</label>
                        <!-- Disabled on dashboard to avoid inline edits; editable from edit page -->
                        <select class="kal-form-control" id="availability" name="availability" disabled>
                            <option value="available" <?php echo ($data['coach']['availability_text'] ?? '') === 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="unavailable" <?php echo ($data['coach']['availability_text'] ?? '') === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                            <option value="flexibility" <?php echo ($data['coach']['availability_text'] ?? '') === 'flexibility' ? 'selected' : ''; ?>>Flexibility</option>
                        </select>
                    </div>
                    <div class="kal-form-group">
                        <label for="hourly_rate">Hourly Rate (LKR)</label>
                        <!-- Show hourly_rate read-only on dashboard; editable on edit page -->
                        <input type="number" class="kal-form-control" id="hourly_rate" name="hourly_rate" readonly
                               value="<?php echo htmlspecialchars($data['coach']['hourly_rate'] ?? str_replace(',', '', $data['coach']['rate'] ?? '')); ?>" min="0">
                    </div>
                </div>
            </div>














            
            <!-- Main Content -->
            <div class="kal-main-content">
                <!-- Basic Information Section -->
                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Basic Information</h3>
                    </div>
                    <div class="kal-form-grid">
                        <div class="kal-form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" class="kal-form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($data['coach']['name']); ?>" readonly>
                        </div>





                        <div class="kal-form-group">  
                            <label for="sport">Sports Specialization</label>
                        
                            <?php
                            // Display single primary sport: if specialization is array use first element, else use string
                            $primarySport = '';
                            if (!empty($data['coach']['specialization'])) {
                                if (is_array($data['coach']['specialization'])) {
                                    $primarySport = $data['coach']['specialization'][0] ?? '';
                                } else {
                                    $primarySport = (string)$data['coach']['specialization'];
                                }
                            }

                            // If sports list provided, map stored key to human-friendly label
                            $primarySportDisplay = $primarySport;
                            if (!empty($data['sports']) && is_array($data['sports'])) {
                                if (isset($data['sports'][$primarySport])) {
                                    $primarySportDisplay = $data['sports'][$primarySport];
                                } else {
                                    // Maybe the stored value equals a label (case-insensitive) — find matching label 
                                    foreach ($data['sports'] as $k => $lbl) {
                                        if (strcasecmp($lbl, $primarySport) === 0) {
                                            $primarySportDisplay = $lbl;
                                            break;
                                        }
                                    }
                                }
                            }
                            ?>

                            <input type="text" class="kal-form-control" id="sport" name="sport" readonly value="<?php echo htmlspecialchars($primarySportDisplay); ?>">
                        </div>





                        <div class="kal-form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="tel" class="kal-form-control" id="mobile" name="mobile" 
                                   value="<?php echo htmlspecialchars($data['coach']['mobile']); ?>" readonly>
                        </div>
                        <div class="kal-form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="kal-form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($data['coach']['email'] ?? ''); ?>" readonly>
                        </div>
                        <div class="kal-form-group">
                            <label for="location">District</label>
                            <input type="text" class="kal-form-control" id="location" name="location" 
                                   value="<?php echo htmlspecialchars($data['coach']['location']); ?>" readonly>
                        </div>
                        <div class="kal-form-group">
                            <label for="certification">Certification</label>
                            <input type="text" class="kal-form-control" id="certification" name="certification" 
                                   value="<?php echo htmlspecialchars($data['coach']['certification'] ?? ''); ?>" readonly>
                        </div>
                        <div class="kal-form-group">
                            <label for="experience">Experience</label>
                            <input type="text" class="kal-form-control" id="experience" name="experience" 
                                   value="<?php echo htmlspecialchars($data['coach']['experience'] ?? ''); ?>" readonly>
                        </div>
                        <div class="kal-form-group">
                            <label for="coaching_type">Coaching Type</label>
                            <input type="text" class="kal-form-control" id="coaching_type" name="coaching_type" 
                                   value="<?php echo htmlspecialchars($data['coach']['coaching_type'] ?? ''); ?>" readonly>
                        </div>
                        <div class="kal-form-group">
                            <label for="primary_availability">Primary Availability</label>
                            <?php
                                // Prefer coach_profiles.availability (registration value) first, fallback to availability_text from card details
                                $primaryAvail = $data['coach']['availability'] ?? $data['coach']['availability_text'] ?? '';
                                // Map some common values to friendly labels
                                $availMap = [
                                    'weekdays' => 'Weekdays',
                                    'weekends' => 'Weekends',
                                    'flexible' => 'Flexible',
                                    'full_time' => 'Full Time',
                                    'part_time' => 'Part Time',
                                    'evenings' => 'Evenings'
                                ];
                                $displayAvail = $availMap[strtolower($primaryAvail)] ?? $primaryAvail;
                            ?>
                            <input type="text" class="kal-form-control" id="primary_availability" name="primary_availability" readonly value="<?php echo htmlspecialchars($displayAvail); ?>">
                        </div>
                    </div>
                </div>

                <!-- About Me Section -->
                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>About Me</h3>
                    </div>
                    <div class="kal-form-group full-width">
                        <label for="bio">Bio/Description</label>
                        <textarea class="kal-form-control" id="bio" name="bio" rows="5"><?php echo htmlspecialchars($data['coach']['bio']); ?></textarea>
                    </div>
                    <div class="kal-form-group full-width">
                        <label for="training_style">Training Style & Philosophy</label>
                        <textarea class="kal-form-control" id="training_style" name="training_style" rows="3" readonly><?php echo htmlspecialchars($data['coach']['training_style'] ?? ''); ?></textarea>
                    </div>
                </div>








                <!-- Languages Section -->
                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Languages Spoken</h3>
                    </div>
                    <div class="kal-tags-container" id="languagesContainer">
                        <?php foreach($data['coach']['languages'] as $lang): ?>
                            <div class="kal-tag">
                                <?php echo htmlspecialchars($lang); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="kal-add-tag">
                        <input type="text" class="kal-form-control" id="newLanguage" placeholder="Add new language">
                        <button type="button" class="kal-edit-btn" data-edit-action="true" disabled onclick="addLanguage()">Add</button>
                    </div>
                </div>




                <!-- Free Training Slots Section -->
                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Free Training Sessions</h3>
                    </div>
                    <div class="kal-slot-grid" id="freeSlotsContainer">
                        <?php foreach($data['coach']['free_slots'] as $index => $slot): ?>
                            <div class="kal-slot-card">
                                <button type="button" class="kal-slot-remove" data-edit-action="true" disabled onclick="removeSlot(this)">×</button>
                                <div class="kal-slot-day"><?php echo htmlspecialchars($slot->day); ?></div>
                                <div class="kal-slot-time"><?php echo htmlspecialchars($slot->time_slot ?? $slot->time); ?></div>
                                <div class="kal-slot-type"><?php echo htmlspecialchars($slot->session_type ?? $slot->type); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="kal-add-slot-form">
                        <div class="kal-form-grid">
                            <div class="kal-form-group">
                                <label for="newSlotDay">Day</label>
                                <select class="kal-form-control" id="newSlotDay">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="kal-form-group">
                                <label for="newSlotTime">Time Slot</label>
                                <input type="text" class="kal-form-control" id="newSlotTime" placeholder="e.g., 4:00 PM - 5:00 PM">
                            </div>
                            <div class="kal-form-group">
                                <label for="newSlotType">Session Type</label>
                                <input type="text" class="kal-form-control" id="newSlotType" placeholder="e.g., Group Session">
                            </div>
                        </div>
                        <button type="button" class="kal-edit-btn" data-edit-action="true" disabled onclick="addFreeSlot()" style="margin-top: 10px;">Add Slot</button>
                    </div>
                </div>



                <!-- Achievements Section -->
                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Achievements & Awards</h3>
                    </div>
                    <ul class="kal-achievement-list" id="achievementsList">
                        <?php foreach($data['coach']['achievements'] as $index => $achievement): ?>
                            <li class="kal-achievement-item">
                                <span class="kal-achievement-text"><?php echo htmlspecialchars($achievement); ?></span>
                                <button type="button" class="kal-remove-btn" data-edit-action="true" disabled onclick="removeAchievement(this)">Remove</button>
                                <input type="hidden" name="achievements[]" value="<?php echo htmlspecialchars($achievement); ?>">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="kal-add-achievement">
                        <input type="text" class="kal-form-control" id="newAchievement" placeholder="Add new achievement">
                        <button type="button" class="kal-edit-btn" data-edit-action="true" disabled onclick="addAchievement()">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
















<script>
// Toggle edit mode for sections (kept for compatibility but not used)
function toggleEdit(section) {
    const inputs = document.querySelectorAll(`#${section} input, #${section} select, #${section} textarea`);
    inputs.forEach(input => {
        input.readOnly = !input.readOnly;
        input.disabled = !input.disabled;
    });
}

// Editing is handled on the dedicated edit page (`coachdash/edit`).

// Tag management functions
function removeTag(button) {
    button.parentElement.remove();
}






function addSpecialization() {
    const input = document.getElementById('newSpecialization');
    const value = input.value.trim();
    if (value) {
        const container = document.getElementById('specializationsContainer');
        const tag = document.createElement('div');
        tag.className = 'kal-tag';
        tag.innerHTML = `
            ${value}
            <button type="button" class="kal-tag-remove" onclick="removeTag(this)">×</button>
            <input type="hidden" name="specializations[]" value="${value}">
        `;
        container.appendChild(tag);
        input.value = '';
    }
}






function addLanguage() {
    const input = document.getElementById('newLanguage');
    const value = input.value.trim();
    if (value) {
        const container = document.getElementById('languagesContainer');
        const tag = document.createElement('div');
        tag.className = 'kal-tag';
        tag.innerHTML = `
            ${value}
            <button type="button" class="kal-tag-remove" onclick="removeTag(this)">×</button>
            <input type="hidden" name="languages[]" value="${value}">
        `;
        container.appendChild(tag);
        input.value = '';
    }
}







// Free slots management
function removeSlot(button) {
    button.parentElement.remove();
}








// Achievements management
function removeAchievement(button) {
    button.parentElement.remove();
}

function addAchievement() {
    const input = document.getElementById('newAchievement');
    const value = input.value.trim();
    if (value) {
        const list = document.getElementById('achievementsList');
        const item = document.createElement('li');
        item.className = 'kal-achievement-item';
        item.innerHTML = `
            <span class="kal-achievement-text">${value}</span>
            <button type="button" class="kal-remove-btn" onclick="removeAchievement(this)">Remove</button>
            <input type="hidden" name="achievements[]" value="${value}">
        `;
        list.appendChild(item);
        input.value = '';
    }
}






// Image upload preview
// Photo upload is only available from the edit page, not the dashboard view
</script>

<?php require APPROOT . '/views/coachdash/inc/footer.php'; ?>